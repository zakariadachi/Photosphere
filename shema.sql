CREATE DATABASE photosphere;
USE photosphere;

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(30) UNIQUE NOT NULL,
    email VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    adresse VARCHAR(30),
    role ENUM("admin", "moderator", "basicuser", "prouser"),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME,
    isSuperAdmin BOOLEAN DEFAULT NULL,
    moderator_level VARCHAR(30) DEFAULT NULL,
    date_debut_abonnement DATE,
    date_fin_abonnement DATE,
    uploadCount INT DEFAULT 0
);

CREATE TABLE album (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    isPublic BOOLEAN DEFAULT TRUE,
    description VARCHAR(600),
    photoCount INT DEFAULT 0,
    updatedAt DATETIME,
    userId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    imageLink VARCHAR(600) NOT NULL,
    imageSize INT,
    dimensions VARCHAR(50),
    status VARCHAR(50),
    viewCount INT DEFAULT 0,
    publishedAt DATETIME NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME,
    userId INT NOT NULL,
    albumId INT NULL,
    FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (albumId) REFERENCES album(id) ON DELETE SET NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    isArchive BOOLEAN DEFAULT FALSE,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT NULL,
    userId INT NOT NULL,
    postId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (postId) REFERENCES post(id) ON DELETE CASCADE
);

CREATE TABLE likes (
    userId INT NOT NULL,
    postId INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userId, postId),
    FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (postId) REFERENCES post(id) ON DELETE CASCADE
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE post_tag (
    post_id INT,
    tag_id INT,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

CREATE TABLE album_post (
    album_id INT,
    post_id INT,
    PRIMARY KEY (album_id, post_id),
    FOREIGN KEY (album_id) REFERENCES album(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE 
);

-- 2. Test Data Insertion
-- Users
INSERT INTO user (user_name, email, password, bio, role, adresse) VALUES
('john_doe', 'john@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Photographe Amateur', 'basicuser', 'Paris'),
('jane_pro', 'jane@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Photographe Pro', 'prouser', 'Lyon'),
('mod_alex', 'alex@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Moderateur', 'moderator', 'Lille'),
('admin_bob', 'bob@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 'admin', 'Marseille');

-- Albums
INSERT INTO album (name, isPublic, description, userId) VALUES
('Vacances Ete', 1, 'Souvenirs de vacances', 1),
('Portfolio Architecture', 0, 'Série architecture', 2);

-- Posts
INSERT INTO post (title, description, imageLink, userId, albumId, status) VALUES
('Coucher de soleil', 'Superbe vue', 'sunset.jpg', 1, 1, 'published'),
('Tour Eiffel', 'Paris la nuit', 'eiffel.jpg', 1, 1, 'published'),
('Immeuble Moderne', 'Design', 'building.jpg', 2, 2, 'published');

-- Tags
INSERT INTO tags (name) VALUES ('nature'), ('voyage'), ('architecture'), ('ville');

-- Post Tags
INSERT INTO post_tag (post_id, tag_id) VALUES (1, 1), (1, 2), (2, 2), (2, 4), (3, 3);

-- Comments
INSERT INTO comments (content, userId, postId) VALUES
('Magnifique photo !', 2, 1),
('Quelle technique ?', 3, 3);

-- Likes
INSERT INTO likes (userId, postId) VALUES (2, 1), (3, 1), (1, 3);



SELECT * FROM album;