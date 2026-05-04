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

INSERT INTO objects (id, titre, epoque, description, prix, prix_initial, image, ench) VALUES
(1,
 'Sceptre',
 'XIV eme siecle',
 'Sceptre royal en or et diamants, ayant appartenu a un roi de France.',
 1000,
 1000,
 'assets/img/obj1.jpg',
 'Aucun encherisseur'),
(2,
 'Couronne',
 'XV eme siecle',
 'Couronne royale en or massif sertie de pierres precieuses.',
 2000,
 2000,
 'assets/img/obj1.jpg',
 'Aucun encherisseur'),
(3,
 'Epee',
 'XII eme siecle',
 'Epee de chevalier templier, lame en acier de Damas.',
 1500,
 1500,
 'assets/img/obj1.jpg',
 'Aucun encherisseur')
ON DUPLICATE KEY UPDATE titre = VALUES(titre), ench = VALUES(ench), prix_initial = VALUES(prix_initial);

INSERT INTO users (username, password, role, argent) VALUES
('amine', '$2y$10$wGv1zDq3S8Xg8n0kqv9O2uCqLd2l7u3Tq8s9o4p.6wKj6Zf5Hh2Hi', 1, 180000)
ON DUPLICATE KEY UPDATE username = VALUES(username);
