CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role TINYINT NOT NULL DEFAULT 1,
    argent INT NOT NULL DEFAULT 180000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS objects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(128) NOT NULL,
    epoque VARCHAR(128) NOT NULL,
    description TEXT NOT NULL,
    prix INT NOT NULL DEFAULT 0,
    prix_initial INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NOT NULL,
    ench VARCHAR(64) NOT NULL DEFAULT 'Aucun encherisseur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS start (
    id INT PRIMARY KEY,
    start TINYINT NOT NULL DEFAULT 0,
    obj INT NOT NULL DEFAULT 1,
    chrono INT NOT NULL DEFAULT 30,
    ended TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO start (id, start, obj, chrono, ended) VALUES (1, 0, 1, 30, 0)
ON DUPLICATE KEY UPDATE start = VALUES(start), obj = VALUES(obj), chrono = VALUES(chrono), ended = VALUES(ended);

-- Force la session actuelle à utiliser l'UTF-8
SET NAMES 'utf8mb4';

INSERT INTO objects (id, titre, epoque, description, prix, prix_initial, image, ench) VALUES
(1, 
 'Pointes de flèches "Ounaniennes" du Tassili n\'Ajjer', 
 'Néolithique (environ 6 000 à 4 000 ans av. J.-C.)', 
 'Ce lot se compose de trois pointes de flèches exceptionnelles en silex blond et jaspe, caractéristiques de la culture ounanienne du Sahara.', 
 2000, 
 2000, 
 'assets/img/1.png', 
 'Aucun encherisseur'),
(2, 
 'Tabzimt de Kabylie', 
 'XVIIIe - XIXe siècle', 
 'Grande broche en argent filigrané, émaux cloisonnés et corail rouge. Pièce maîtresse de l\'orfèvrerie berbère.', 
 12500, 
 12500, 
 'assets/img/2.png', 
 'Aucun encherisseur'),
(3, 
 'Chapiteau de la Mansourah', 
 'XIVe siècle', 
 'Chapiteau en marbre blanc sculpté, vestige de la cité impériale de Tlemcen.', 
 35000, 
 35000, 
 'assets/img/3.png', 
 'Aucun encherisseur'),
(4, 
 'Astrolabe d\'Alger', 
 'XVIIIe siècle', 
 'Instrument astronomique en laiton ciselé. Chef-d\'œuvre de précision de la Régence d\'Alger.', 
 65000, 
 65000, 
 'assets/img/4.png', 
 'Aucun encherisseur'),
(5, 
 'Statère d\'Or de Massinissa', 
 'IIe siècle av. J.-C.', 
 'Pièce de monnaie royale numide représentant le roi Massinissa et un cheval au galop.', 
 110000, 
 110000, 
 'assets/img/5.png', 
 'Aucun encherisseur'),
(6, 
 'Epée de l\'Emir Abdelkader', 
 'Milieu du XIXe siècle', 
 'Cette arme d\'apparat a appartenu à l\'Émir Abdelkader. Poignée en ivoire et fourreau d\'argent ciselé.', 
 1800000, 
 1800000, 
 'assets/img/6.png', 
 'Aucun encherisseur')
ON DUPLICATE KEY UPDATE 
    titre = VALUES(titre), 
    epoque = VALUES(epoque),
    description = VALUES(description),
    prix = VALUES(prix),
    prix_initial = VALUES(prix_initial),
    image = VALUES(image),
    ench = VALUES(ench);

INSERT INTO users (username, password, role, argent) VALUES
('amine', '$2y$10$wGv1zDq3S8Xg8n0kqv9O2uCqLd2l7u3Tq8s9o4p.6wKj6Zf5Hh2Hi', 1, 180000)
ON DUPLICATE KEY UPDATE username = VALUES(username);
