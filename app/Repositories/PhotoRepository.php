<?php

namespace App\Repositories;

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Photo.php';

use App\Entities\Photo;
use PDO;
use Exception;
use Throwable;

class PhotoRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \Database::getConnection();
    }

    public function findById(int $id): ?Photo {
        $sql = "SELECT * FROM post WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $this->hydrate($row) : null;
        }
        return null;
    }

    public function getLatest(int $limit = 10): array {
        $sql = "SELECT * FROM post ORDER BY createdAt DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $photos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photos[] = $this->hydrate($row);
        }
        return $photos;
    }

    public function search(string $query): array {
        $sql = "SELECT * FROM post WHERE (title LIKE :q OR description LIKE :q) AND status = 'published' ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':q', '%' . $query . '%');
        $stmt->execute();

        $photos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photos[] = $this->hydrate($row);
        }
        return $photos;
    }

    public function archive(int $id): bool {
        $sql = "UPDATE post SET status = 'archived' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function saveWithTags(Photo $photo, array $tags): bool
    {
        // Validation
        $count = count($tags);
        if ($count < 1) {
            throw new Exception("Erreur : Il faut au moins 1 tag.");
        }
        
        if ($count > 10) {
            throw new Exception("Erreur : Pas plus de 10 tags autorisés.");
        }

        try {
            $this->pdo->beginTransaction();

            // Insérer
            $sqlPost = "INSERT INTO post (title, description, imageLink, userId, albumId, status, createdAt) 
                        VALUES (:title, :description, :imageLink, :userId, :albumId, :status, NOW())";
            
            $stmtPost = $this->pdo->prepare($sqlPost);
            
            $stmtPost->bindValue(':title', $photo->getTitle());
            $stmtPost->bindValue(':description', $photo->getDescription());
            $stmtPost->bindValue(':imageLink', $photo->getImageLink());
            $stmtPost->bindValue(':userId', $photo->getUserId());
            
            // Gestion explicite pour albumId
            if ($photo->getAlbumId() != null) {
                $stmtPost->bindValue(':albumId', $photo->getAlbumId());
            } else {
                $stmtPost->bindValue(':albumId', null);
            }

            $stmtPost->bindValue(':status', 'published');
            
            $stmtPost->execute();
            
            // Récupérer l'ID
            $postId = $this->pdo->lastInsertId();

            // Traitement des Tags
            $tagsGeneres = [];

            foreach ($tags as $rawTag) {
                $tagName = strtolower(trim($rawTag));

                if (in_array($tagName, $tagsGeneres) == false) {
                    $tagsGeneres[] = $tagName;

                    $sqlCheck = "SELECT id FROM tags WHERE name = :name";
                    $stmtCheck = $this->pdo->prepare($sqlCheck);
                    $stmtCheck->bindValue(':name', $tagName);
                    $stmtCheck->execute();
                    
                    $existingTag = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                    $tagId = 0;

                    if ($existingTag) {
                        $tagId = $existingTag['id'];
                        // Increment photoCount
                        $this->pdo->prepare("UPDATE tags SET photoCount = photoCount + 1 WHERE id = ?")->execute([$tagId]);
                    } else {
                        // Create tag with slug and photoCount = 1
                        $sqlInsertTag = "INSERT INTO tags (name, slug, photoCount) VALUES (:name, :slug, 1)";
                        $stmtInsertTag = $this->pdo->prepare($sqlInsertTag);
                        require_once __DIR__ . '/../Entities/Tag.php';
                        $stmtInsertTag->bindValue(':name', $tagName);
                        $stmtInsertTag->bindValue(':slug', \App\Entities\Tag::normalizeSlug($tagName));
                        $stmtInsertTag->execute();
                        
                        $tagId = $this->pdo->lastInsertId();
                    }

                    $sqlLink = "INSERT INTO post_tag (post_id, tag_id) VALUES (:postId, :tagId)";
                    $stmtLink = $this->pdo->prepare($sqlLink);
                    $stmtLink->bindValue(':postId', $postId);
                    $stmtLink->bindValue(':tagId', $tagId);
                    $stmtLink->execute();
                }
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e; 
        }
    }

    private function hydrate(array $data): Photo {        
        return new Photo(
            $data['id'],
            $data['title'],
            $data['description'] ?? null,
            $data['imageLink'],
            $data['imageSize'] ?? 0,
            $data['dimensions'] ?? null,
            $data['status'] ?? 'draft',
            $data['viewCount'] ?? 0,
            $data['publishedAt'] ?? null,
            $data['createdAt'] ?? null,
            $data['updatedAt'] ?? null,
            $data['userId'],
            $data['albumId'] ?? null
        );
    }
}
