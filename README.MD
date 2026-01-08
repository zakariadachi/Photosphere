# Photosphere - Plateforme de Partage de Photos

Photosphere est une application web de gestion et partage de photos développée en PHP orienté objet, avec une architecture MVC et une gestion avancée des utilisateurs, albums, et tags.

## 🎯 Fonctionnalités Principales

### Gestion des Utilisateurs
- **Types d'utilisateurs** : Administrator, Moderator, ProUser, BasicUser
- **Authentification** : Système de login sécurisé
- **Archivage** : Possibilité d'archiver les comptes utilisateurs
- **Permissions** : Gestion des droits selon le rôle

### Gestion des Photos
- **Upload** : Téléchargement de photos avec métadonnées
- **Tags** : Système de tags pour catégoriser les photos
- **Recherche** : Recherche par titre, description, ou tags
- **Archivage** : Archivage des photos
- **Interactions** : Likes et commentaires

### Gestion des Albums
- **Création** : Albums publics ou privés
- **Organisation** : Association de photos aux albums (Many-to-Many)
- **Permissions** : Albums privés réservés aux utilisateurs Pro/Moderator/Admin

### Système de Tags
- **Tags populaires** : Statistiques d'utilisation
- **Recherche** : Recherche de tags par nom
- **Association** : Liaison flexible entre photos et tags
- **Fusion de tags** : Merge de tags en double (PhotoCommunity)
- **Statistiques avancées** : Stats détaillées par tag (PhotoCommunity)

### Interactions Sociales (PhotoCommunity)
- **Commentaires** : Système complet de commentaires avec archivage
- **Likes** : Système de likes avec compteurs et vérifications
- **Repositories dédiés** : `CommentRepository` et `LikeRepository`

## 🏗️ Architecture

### Structure du Projet
```
photosphere/
├── app/
│   ├── Core/
│   │   └── Database.php          # Singleton de connexion DB
│   ├── Entities/
│   │   ├── User.php              # Classe abstraite User
│   │   ├── Administrator.php
│   │   ├── Moderator.php
│   │   ├── ProUser.php
│   │   ├── BasicUser.php
│   │   ├── Photo.php
│   │   ├── Album.php
│   │   ├── Tag.php
│   │   ├── Comment.php           # NEW (PhotoCommunity)
│   │   └── Like.php              # NEW (PhotoCommunity)
│   ├── Repositories/
│   │   ├── UserRepository.php
│   │   ├── PhotoRepository.php
│   │   ├── AlbumRepository.php
│   │   ├── TagRepository.php
│   │   ├── CommentRepository.php # NEW (PhotoCommunity)
│   │   └── LikeRepository.php    # NEW (PhotoCommunity)
│   ├── Services/
│   │   └── UserFactory.php       # Factory pour créer les utilisateurs
│   ├── Traits/
│   │   ├── TaggableTrait.php
│   │   ├── TimestampableTrait.php
│   │   ├── LikeableTrait.php
│   │   └── CommentableTrait.php
│   └── Interfaces/
│       ├── RepositoryInterface.php
│       ├── Taggable.php
│       ├── Commentable.php
│       └── Likeable.php
├── migrations/
│   └── 01_add_status_to_user.php
├── tests/
│   ├── test_functional_flow.php  # Test principal (10 étapes)
│   ├── day3_tests.php
│   ├── test_inheritance.php
│   └── test_interfaces_traits.php
├── shema.sql                     # Schéma de base de données
└── README.md
```

### Base de Données

**Tables principales** :
- `user` : Utilisateurs avec rôles et statuts
- `post` : Photos avec métadonnées
- `album` : Albums de photos
- `tags` : Tags disponibles
- `post_tag` : Relation Many-to-Many (Photos ↔ Tags)
- `album_post` : Relation Many-to-Many (Albums ↔ Photos)
- `comments` : Commentaires sur les photos
- `likes` : Likes sur les photos

## 🚀 Installation

### Prérequis
- PHP 8.0+
- MySQL/MariaDB
- XAMPP (ou équivalent)

### Configuration

1. **Cloner le projet**
   ```bash
   cd c:\xampp\htdocs\
   git clone <votre-repo> photosphere
   ```

2. **Créer la base de données**
   ```bash
   mysql -u root -p < shema.sql
   ```

3. **Exécuter les migrations**
   ```bash
   php migrations/01_add_status_to_user.php
   ```

4. **Configurer la connexion DB**
   
   Modifier `app/Core/Database.php` si nécessaire :
   ```php
   private $host = 'localhost';
   private $dbname = 'photosphere';
   private $username = 'root';
   private $password = '';
   ```

## 🧪 Tests

### Test Principal
Exécuter le test de flux fonctionnel complet :
```bash
php tests/test_functional_flow.php
```

Ce test vérifie :
- ✅ Création d'utilisateur
- ✅ Création d'album
- ✅ Création de photo avec tags
- ✅ Liaison photo-album
- ✅ Recherche de photos
- ✅ Interactions (Traits)
- ✅ Archivage d'utilisateur
- ✅ **Commentaires** (PhotoCommunity)
- ✅ **Likes** (PhotoCommunity)
- ✅ **Statistiques de tags** (PhotoCommunity)

### Tests Unitaires
```bash
php tests/test_inheritance.php
php tests/test_interfaces_traits.php
php tests/day3_tests.php
```

## 📋 Utilisation

### Exemple : Créer un Utilisateur
```php
use App\Repositories\UserRepository;
use App\Entities\BasicUser;

$userRepo = new UserRepository();
$user = new BasicUser(0, "john_doe", "john@example.com", "password123", "basicuser", date('Y-m-d H:i:s'));
$userId = $userRepo->save($user);
```

### Exemple : Créer un Album
```php
use App\Repositories\AlbumRepository;
use App\Entities\Album;

$albumRepo = new AlbumRepository();
$album = new Album(0, "Vacances 2024", true, "Photos de vacances", 0, null, $userId);
$albumRepo->save($album);
```

### Exemple : Uploader une Photo avec Tags
```php
use App\Repositories\PhotoRepository;
use App\Entities\Photo;

$photoRepo = new PhotoRepository();
$photo = new Photo(0, "Coucher de soleil", "Belle vue", "sunset.jpg", 2048, "1920x1080", "draft", 0, null, null, null, $userId, null);
$photoRepo->saveWithTags($photo, ['nature', 'voyage', 'sunset']);
```

### Exemple : Ajouter un Commentaire (PhotoCommunity)
```php
use App\Repositories\CommentRepository;

$commentRepo = new CommentRepository();
$comment = $commentRepo->create("Superbe photo !", $userId, $photoId);
```

### Exemple : Ajouter un Like (PhotoCommunity)
```php
use App\Repositories\LikeRepository;

$likeRepo = new LikeRepository();
$likeRepo->addLike($userId, $photoId);
```

### Exemple : Fusionner des Tags (PhotoCommunity)
```php
use App\Repositories\TagRepository;

$tagRepo = new TagRepository();
// Fusionner 'sunset-pics' dans 'sunset'
$tagRepo->mergeTags('sunset-pics', 'sunset');
```

### Exemple : Obtenir des Statistiques de Tag (PhotoCommunity)
```php
$stats = $tagRepo->getTagStats('nature');
echo "Tag: " . $stats['tag']->getName();
echo "Photos: " . $stats['totalPhotos'];
echo "Utilisateurs: " . $stats['totalUsers'];
```

## 🔑 Concepts POO Utilisés

- **Héritage** : User → Administrator, Moderator, ProUser, BasicUser
- **Abstraction** : Classe abstraite `User`
- **Interfaces** : `RepositoryInterface`, `Taggable`, `Commentable`, `Likeable`
- **Traits** : Réutilisation de code (TaggableTrait, LikeableTrait, etc.)
- **Factory Pattern** : `UserFactory` pour instancier les bons types d'utilisateurs
- **Repository Pattern** : Séparation logique métier / accès données
- **Singleton** : `Database` pour une connexion unique
- **Encapsulation** : Propriétés privées avec getters/setters

## 📊 Fonctionnalités Avancées

### Système de Commentaires
- Création de commentaires
- Récupération par photo
- Archivage de commentaires
- Comptage de commentaires

### Système de Likes
- Ajout/suppression de likes
- Vérification si un utilisateur a liké
- Comptage de likes par photo
- Récupération des likes par utilisateur

### Gestion Avancée des Tags
- Normalisation automatique des slugs
- Fusion de tags en double
- Statistiques détaillées (nombre de photos, utilisateurs)
- Recherche intelligente

## 🎯 Comparaison avec les Projets de Référence

| Fonctionnalité | PHOTOSPHERE_POO | PhotoCommunity | Photosphere |
|----------------|-----------------|----------------|-------------|
| Gestion Utilisateurs | ✅ | ✅ | ✅ |
| Albums | ✅ | ✅ | ✅ |
| Photos | ✅ | ✅ | ✅ |
| Tags | ✅ | ✅ | ✅ |
| Archivage | ✅ | ✅ | ✅ |
| Entités Comment/Like | ❌ | ✅ | ✅ |
| CommentRepository | ❌ | ✅ | ✅ |
| LikeRepository | ❌ | ✅ | ✅ |
| Tag Stats | ❌ | ✅ | ✅ |
| Tag Merging | ❌ | ✅ | ✅ |
| Slug System | ❌ | ✅ | ✅ |

## 📝 Licence

Ce projet est développé dans un cadre éducatif.

## 👥 Auteur

Développé dans le cadre d'un projet de formation en Programmation Orientée Objet (POO) avec PHP.

---

## 🎉 Statut du Projet

✅ **100% Fonctionnel** - Toutes les fonctionnalités de `PHOTOSPHERE_POO` et `PHOTOSPHERE-PhotoCommunity` sont implémentées et testées.
