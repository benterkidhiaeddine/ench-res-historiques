# ChronoEncheres - Boilerplate initial

Boilerplate PHP + MySQL (dockerise) pour un projet d encheres historiques avec interactions multi-utilisateurs en quasi temps reel via AJAX.

## Stack mise en oeuvre

- HTML/CSS (layout grid + flex, responsive)
- JavaScript (DOM, evenements, JSON)
- PHP (sessions, formulaires, generation HTML, API JSON)
- AJAX (polling des encheres et envoi des mises)
- MySQL via Docker

## Structure du projet

```
.
|-- docker-compose.yml        # orchestration complete (web + php + db + pma)
|-- config/
|   |-- nginx/default.conf    # config nginx (fastcgi vers php-fpm)
|   `-- php/Dockerfile        # image php-fpm + pdo_mysql
|-- db/
|   `-- init.sql              # schema + donnees initiales (charge au 1er up)
|-- includes/
|   `-- db.php                # connexion PDO partagee (hors web root)
`-- public/                   # document root servi par nginx
    |-- index.php login.php register.php admin.php user.php
    |-- api/                  # endpoints JSON (fetch cote client)
    |   |-- majobj.php majbud.php verifstart.php start.php
    `-- assets/
        |-- css/  js/  img/
```

## Demarrage rapide

```bash
docker compose up --build
```

Au premier lancement, `db/init.sql` cree les tables (`users`, `objects`, `start`) et insere les donnees de demonstration.

Acces:

- Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (root / rootpassword)
- MySQL host local: `127.0.0.1:3307` (user ench_user / ench_password)

## Fonctionnalites incluses

- Connexion legere via pseudo (session PHP)
- Catalogue d objets historiques
- Salle d enchere par objet
- Mises concurrentes avec verrou SQL (`FOR UPDATE`)
- Flux d activite commun a tous les utilisateurs
- Rafraichissement en direct des prix et du nombre de mises

## Pistes pour aller plus loin

- WebSockets pour push temps reel sans polling
- Upload d images d objets (PHP fichiers)
- Historique des encherisseurs par objet
- Fermeture automatique et declaration du gagnant
