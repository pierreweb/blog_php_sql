-- Définir l'encodage UTF-8 dès le début
SET
    NAMES utf8mb4;

SET
    CHARACTER SET utf8mb4;

-- Modifier la base de données pour utiliser utf8mb4
ALTER DATABASE blog_Conan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Création de la table articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT NULL,
    content TEXT DEFAULT NULL,
    imageUrl VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Insertion des articles
INSERT INTO
    articles (title, category, content, imageUrl, created_at)
VALUES
    (
        'La prophétie des cendres',
        'scénario',
        'Au cœur des steppes du Nord, un ancien manuscrit refait surface, annonçant le retour d’un dieu oublié. Les joueurs devront affronter des tribus sauvages, des tempêtes mystiques et un destin incertain pour empêcher la fin du monde connu.',
        '/images/articles/article1.jpg',
        '2025-03-10 14:00:00'
    ),
    (
        'Le sorcier de Shadizar',
        'scénario',
        'Dans les ruelles ombragées de Shadizar, un sorcier fomente une révolte à l’aide d’artefacts anciens. Ce scénario mêle intrigue urbaine, corruption et magie noire dans une ambiance étouffante de décadence orientale.',
        '/images/articles/article2.jpg',
        '2025-01-22 09:30:00'
    ),
    (
        'L\'ombre de Thog',
        'scénario',
        'Une silhouette monstrueuse émerge des abysses lors d’une expédition dans des cavernes englouties. Entre mythe lovecraftien et barbarie brutale, ce scénario teste la santé mentale et la loyauté des personnages.',
        '/images/articles/article3.jpg',
        '2025-04-05 17:45:00'
    ),
    (
        'Les ruines de Valkarth',
        'scénario',
        'Les PJ sont engagés comme mercenaires pour explorer des ruines enneigées abandonnées depuis des siècles. Ce donjon glacé renferme des pièges anciens, des esprits guerriers et un artefact capable de geler le temps.',
        '/images/articles/article4.jpg',
        '2024-11-12 16:20:00'
    ),
    (
        'Esquisse du colosse',
        'illustration',
        'Une illustration puissante représentant Conan brandissant son épée au sommet d’un mont ensanglanté. Idéal pour une couverture ou comme inspiration de PNJ barbare dans vos campagnes.',
        '/images/articles/article5.jpg',
        '2024-09-02 11:15:00'
    ),
    (
        'Portrait d\'une prêtresse stygienne',
        'illustration',
        'Mystérieuse et envoûtante, cette illustration d’une prêtresse de Stygie suggère à la fois puissance divine et trahison imminente. Utilisable comme antagoniste ou alliée ambiguë.',
        '/images/articles/article6.jpg',
        '2024-08-18 08:40:00'
    );

-- Création de la table tags
CREATE TABLE IF NOT EXISTS tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL
) DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Insertion des tags
INSERT INTO
    tags (name)
VALUES
    ('barbare'),
    ('magie'),
    ('aventure'),
    ('monstre'),
    ('sorcier'),
    ('illustration');

-- Création de la table article_tags pour gérer la relation entre articles et tags
CREATE TABLE article_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Insertion des relations entre articles et tags
-- Lier les articles aux tags
INSERT INTO
    article_tags (article_id, tag_id)
VALUES
    (1, 2),
    -- L'article 1 est lié au tag 2
    (1, 3),
    -- L'article 1 est aussi lié au tag 3
    (2, 1),
    -- L'article 2 est lié au tag 1
    (3, 4),
    -- L'article 3 est lié au tag 4
    (4, 2),
    -- L'article 4 est lié au tag 2
    (5, 5);

-- L'article 5 est lié au tag 5
(6, 6);

-- L'article 6 est lié au tag 6