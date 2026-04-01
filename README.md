# ChronoEncheres - Boilerplate initial

Boilerplate PHP + MySQL (dockerise) pour un projet d encheres historiques avec interactions multi-utilisateurs en quasi temps reel via AJAX.

## Stack mise en oeuvre

- HTML/CSS (layout grid + flex, responsive)
- JavaScript (DOM, evenements, JSON)
- PHP (sessions, formulaires, generation HTML, API JSON)
- AJAX (polling des encheres et envoi des mises)
- MySQL via Docker

## Demarrage rapide

```bash
docker compose up --build
```

Acces:

- Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- MySQL host local: `127.0.0.1:3307`

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
