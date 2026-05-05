# ChronoEncheres

Application PHP + MySQL (dockerisée) pour des enchères d'objets historiques avec interactions multi-utilisateurs en quasi temps réel via AJAX.

## Stack mise en oeuvre

- HTML/CSS (layout grid + flex, responsive, police Inter)
- JavaScript (DOM, événements, JSON, polling AJAX)
- PHP (sessions, formulaires, génération HTML, API JSON)
- MySQL via Docker

## Structure du projet

```
.
|-- docker-compose.yml        # orchestration complète (web + php + db + pma)
|-- config/
|   |-- nginx/default.conf    # config nginx (fastcgi vers php-fpm)
|   `-- php/Dockerfile        # image php-fpm + pdo_mysql
|-- db/
|   `-- init.sql              # schéma + données initiales (chargé au 1er up)
|-- includes/
|   `-- db.php                # connexion PDO partagée (hors web root)
`-- public/                   # document root servi par nginx
    |-- index.php login.php register.php admin.php user.php
    |-- api/                  # endpoints JSON (fetch côté client)
    |   |-- majobj.php majbud.php verifstart.php start.php
    |   |-- encherir.php chrono.php chronouser.php
    |   |-- results.php reset.php
    `-- assets/
        |-- css/  js/  img/
```

## Démarrage rapide

```bash
docker compose up --build
```

Au premier lancement, `db/init.sql` crée les tables (`users`, `objects`, `start`) et insère les données de démonstration.

Accès :

- Application : http://localhost:8080
- phpMyAdmin : http://localhost:8081 (root / rootpassword)
- MySQL host local : `127.0.0.1:3307` (user ench_user / ench_password)

## Fonctionnalités

- Connexion légère via pseudo (session PHP)
- Catalogue de 6 objets historiques algériens
- Salle d'enchère par objet avec chrono en direct
- Mises concurrentes (+500 € par enchère)
- Décompte de 30 secondes par objet ; ramené à 5 secondes dès qu'une mise est placée
- Déclaration automatique du gagnant à la fin du chrono
- Affichage des résultats finaux en temps réel sans rechargement de page (overlay côté utilisateur et admin)
- Réinitialisation complète de la session par l'admin (budgets, prix, chrono)
- Interface responsive (mobile + desktop)
- Police Inter pour une meilleure lisibilité
- Messages d'erreur et de succès visibles sur la page de connexion/inscription
- Notification toast non-bloquante en cas de budget insuffisant

## Pistes pour aller plus loin

- WebSockets pour push temps réel sans polling
- Upload d'images d'objets (PHP fichiers)
- Historique des enchérisseurs par objet
- Authentification renforcée (rôles, protection CSRF)
