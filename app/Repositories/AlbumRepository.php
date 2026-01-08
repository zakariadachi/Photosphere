<?php

namespace App\Repositories;

require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Album.php'; 

use App\Entities\Album;
use App\Repositories\RepositoryInterface;
use PDO;
use Exception;
use Throwable;

class AlbumRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \Database::getConnection();
    }

    public function findById(int $id): ?Album {
        $sql = "SELECT * FROM album WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $this->hydrate($row) : null;
        }
        return null;
    }

    public function findPublic(): array {
        $sql = "SELECT * FROM album WHERE isPublic = 1 ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        
        $albums = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $albums[] = $this->hydrate($row);
        }
        return $albums;
    }

    public function findUserAlbums(int $userId): array {
        $sql = "SELECT * FROM album WHERE userId = :userId ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();

        $albums = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $albums[] = $this->hydrate($row);
        }
        return $albums;
    }

    public function save(Album $album): bool {
        if ($album->getId() === null || $album->getId() == 0) {
            // Insert
            $sql = "INSERT INTO album (name, description, isPublic, userId) VALUES (:name, :desc, :public, :user)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":name", $album->getName());
            $stmt->bindValue(":desc", $album->getDescription());
            $stmt->bindValue(":public", (int)$album->isPublic(), PDO::PARAM_INT);
            $stmt->bindValue(":user", $album->getUserId(), PDO::PARAM_INT);
            
            return $stmt->execute();   
        } else {
            // Update
            $sql = "UPDATE album SET name = :name, description = :desc, isPublic = :public WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":name", $album->getName());
            $stmt->bindValue(":desc", $album->getDescription());
            $stmt->bindValue(":public", (int)$album->isPublic(), PDO::PARAM_INT);
            $stmt->bindValue(":id", $album->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        }
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM album WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addPhotoToAlbum(int $photoId, int $albumId, int $userId): bool {
        // ownership
        $album = $this->findById($albumId);
        if (!$album || $album->getUserId() !== $userId) {
            return false;
        }

        // Check existing link
        $sqlCheck = "SELECT * FROM album_post WHERE album_id = :aid AND post_id = :pid";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->bindValue(':aid', $albumId);
        $stmtCheck->bindValue(':pid', $photoId);
        $stmtCheck->execute();
        
        if ($stmtCheck->rowCount() > 0) {
            return true;
        }

        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO album_post (album_id, post_id) VALUES (:aid, :pid)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':aid', $albumId);
            $stmt->bindValue(':pid', $photoId);
            $stmt->execute();
            
            // Increment count
            $sqlUpdate = "UPDATE album SET photoCount = photoCount + 1 WHERE id = :id";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':id', $albumId);
            $stmtUpdate->execute();

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function removePhotoFromAlbum(int $photoId, int $albumId, int $userId): bool {
         // Verify ownership
         $album = $this->findById($albumId);
         if (!$album || $album->getUserId() !== $userId) {
             return false;
         }
 
         try {
             $this->pdo->beginTransaction();
 
             $sql = "DELETE FROM album_post WHERE album_id = :aid AND post_id = :pid";
             $stmt = $this->pdo->prepare($sql);
             $stmt->bindValue(':aid', $albumId);
             $stmt->bindValue(':pid', $photoId);
             $stmt->execute();
 
             if ($stmt->rowCount() > 0) {
                 // Decrement count
                 $sqlUpdate = "UPDATE album SET photoCount = photoCount - 1 WHERE id = :id AND photoCount > 0";
                 $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                 $stmtUpdate->bindValue(':id', $albumId);
                 $stmtUpdate->execute();
             }
 
             $this->pdo->commit();
             return true;
         } catch (Throwable $e) {
             $this->pdo->rollBack();
             return false;
         }
    }

    public function getAlbumWithPhotos(int $albumId): ?array {
        $album = $this->findById($albumId);
        if (!$album) return null;

        $sql = "SELECT p.* FROM post p
                JOIN album_post ap ON p.id = ap.post_id
                WHERE ap.album_id = :aid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':aid', $albumId);
        $stmt->execute();
        
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'album' => $album,
            'photos' => $photos
        ];
    }

    private function hydrate(array $data): Album {
        // Constructor
        return new Album(
            $data['id'],
            $data['name'],
            (bool)$data['isPublic'],
            $data['description'] ?? null,
            $data['photoCount'] ?? 0,
            $data['updatedAt'] ?? null,
            $data['userId']
        );
    }
}
