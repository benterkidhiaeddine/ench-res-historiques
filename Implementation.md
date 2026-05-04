# Plan — Fermeture automatique et déclaration des gagnants

## Context

Le README liste comme prochaine étape : "Fermeture automatique et declaration du gagnant". L'enchère côté backend gère déjà la transition entre objets (cf. [public/api/chrono.php](public/api/chrono.php)) et marque `start.start = 0` quand le dernier objet est vendu, mais **rien n'est affiché côté user/admin** : le frontend reste figé sur le dernier objet.

L'utilisateur veut :
1. Un **overlay fermable** côté user qui annonce les gagnants quand l'enchère est terminée.
2. L'**admin doit aussi voir les gagnants** (même overlay).
3. Un **bouton "Reset" côté admin** pour relancer une nouvelle session (objets remis aux prix de départ, soldes restaurés, état remis à zéro).

---

## Décisions de design

- **Distinction "non commencé" / "en cours" / "terminé"** : ajouter une colonne `start.ended TINYINT NOT NULL DEFAULT 0`. Plus propre que de surcharger `start` avec une 3e valeur, et le frontend lit déjà `start.start` via `verifstart.php`.
- **Reset des prix initiaux** : ajouter `objects.prix_initial INT NOT NULL DEFAULT 0`. Migration : `UPDATE objects SET prix_initial = prix` pour les lignes existantes. Toutes les futures lignes devront fournir cette valeur explicitement dans le seed.
- **Reset de l'argent** : `UPDATE users SET argent = 180000` (constante hardcodée, identique au `DEFAULT` du schéma). On ne stocke pas un `argent_initial` par user — c'est un projet de démo, tous repartent du même solde.
- **Auth admin** : `admin.php` et `api/reset.php` n'ont aucune protection actuellement (pas de notion de role côté session). Je n'ajoute pas d'auth — hors scope, à noter pour plus tard.
- **Fermeture de l'overlay** : bouton `×` en haut à droite. Une fois fermé, on stocke un flag JS (`resultsDismissed`) pour éviter qu'il se ré-ouvre à chaque tick de polling. Si l'admin reset, le flag est remis à zéro.

---

## Implémentation

### 1. Schéma BD

**Migration live (ALTER TABLE additive, sans perte) :**
```sql
ALTER TABLE start   ADD COLUMN ended TINYINT NOT NULL DEFAULT 0;
ALTER TABLE objects ADD COLUMN prix_initial INT NOT NULL DEFAULT 0;
UPDATE objects SET prix_initial = prix WHERE prix_initial = 0;
```

**[db/init.sql](db/init.sql)** : ajouter `ended` à la définition `start`, `prix_initial` à `objects`, et fournir la valeur dans les `INSERT INTO objects ... VALUES`.

### 2. Backend

**[public/api/chrono.php](public/api/chrono.php)** — modifier la branche "plus d'objet suivant" pour aussi `ended = 1` :
```php
$stmt = $pdo->query("UPDATE start SET chrono = 0, start = 0, ended = 1 WHERE id = 1");
```

**Nouveau : `public/api/results.php`** — endpoint lu par les deux frontends :
```php
<?php
header('Content-Type: application/json');
require __DIR__ . '/../../includes/db.php';
try {
    $stmt = $pdo->query("SELECT ended FROM start WHERE id = 1");
    $s = $stmt->fetch();
    $stmt = $pdo->query("SELECT id, titre, prix, ench, image FROM objects ORDER BY id ASC");
    echo json_encode([
        "ended"   => (bool) $s['ended'],
        "results" => $stmt->fetchAll(),
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
```

**Nouveau : `public/api/reset.php`** — admin only (pas d'auth pour l'instant), wrappé dans une transaction :
```php
<?php
header('Content-Type: application/json');
require __DIR__ . '/../../includes/db.php';
try {
    $pdo->beginTransaction();
    $pdo->exec("UPDATE objects SET prix = prix_initial, ench = 'Aucun encherisseur'");
    $pdo->exec("UPDATE start SET start = 0, obj = 1, chrono = 30, ended = 0 WHERE id = 1");
    $pdo->exec("UPDATE users SET argent = 180000");
    $pdo->commit();
    echo json_encode(["ok" => true]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
```

### 3. Frontend — HTML

**[public/user.php](public/user.php)** : ajouter avant `</body>` un overlay caché par défaut. Passer le username connecté en `data-user` sur `<body>` pour highlight côté JS.

```html
<body data-user="<?php echo htmlspecialchars($username); ?>">
...
<div id="resultsOverlay" hidden>
    <div id="resultsBox">
        <button id="resultsClose" type="button">×</button>
        <h1>Enchères terminées</h1>
        <ul id="resultsList"></ul>
    </div>
</div>
```

**[public/admin.php](public/admin.php)** : ajouter `<button id="reset">Reset</button>` à côté de `Start`, et le même overlay (sans `data-user`, donc pas de highlight gagnant).

### 4. Frontend — JS

**[public/assets/js/scriptuser.js](public/assets/js/scriptuser.js)** :
- Ajouter handler du bouton `#resultsClose` qui set un flag `resultsDismissed = true`.
- Dans `verifstart()`, si `data.start === 0` ET on poll `results.php` ; si `ended === true` ET `!resultsDismissed`, remplir la liste et révéler l'overlay. Si la liste est déjà révélée, ne rien refaire (idempotent).
- Quand l'overlay est révélé, arrêter les `setInterval` de `majobj`/`majbud` (on ne polle plus la salle d'enchères en cours, elle est terminée). Mais `verifstart` continue à tourner pour détecter un reset (`ended=0` à nouveau → fermer overlay et redémarrer le flow normal).

**Nouveau fichier `public/assets/js/results-overlay.js`** (commun aux deux pages) ? **Non** — pour rester minimal, dupliquer la logique d'affichage dans `scriptuser.js` et `scriptadmin.js`. Le code est court (~20 lignes), pas besoin d'abstraire.

**[public/assets/js/scriptadmin.js](public/assets/js/scriptadmin.js)** :
- Garder le `start()` existant.
- Ajouter handler du bouton `#reset` : `fetch('api/reset.php', {method:'POST'})` puis fermer l'overlay et reset `resultsDismissed = false`.
- Lancer un `setInterval` permanent qui poll `api/results.php` toutes les 1s pour détecter `ended=true` → remplir et révéler l'overlay. Indépendant du chrono.
- Handler `#resultsClose` identique.

### 5. CSS

**[public/assets/css/styleuser.css](public/assets/css/styleuser.css)** et **[public/assets/css/styleadmin.css](public/assets/css/styleadmin.css)** : ajouter styles overlay (position fixed, fond semi-transparent, box centrée, bouton close en absolute, liste avec ligne par objet, highlight `.winner-self` pour le user connecté).

---

## Critical files

- [db/init.sql](db/init.sql) — schéma + seed
- [public/api/chrono.php](public/api/chrono.php) — set ended=1
- [public/api/results.php](public/api/results.php) — **nouveau**
- [public/api/reset.php](public/api/reset.php) — **nouveau**
- [public/user.php](public/user.php) — overlay HTML + data-user
- [public/admin.php](public/admin.php) — bouton reset + overlay
- [public/assets/js/scriptuser.js](public/assets/js/scriptuser.js) — polling results, overlay handlers
- [public/assets/js/scriptadmin.js](public/assets/js/scriptadmin.js) — reset + polling results
- [public/assets/css/styleuser.css](public/assets/css/styleuser.css) — styles overlay
- [public/assets/css/styleadmin.css](public/assets/css/styleadmin.css) — styles overlay

## Vérification end-to-end

1. Appliquer la migration `ALTER TABLE` ; vérifier `DESCRIBE start` contient `ended`, `DESCRIBE objects` contient `prix_initial`.
2. Recharger les conteneurs (bind mounts → pas de rebuild).
3. Ouvrir admin.php → cliquer Start. Ouvrir user.php avec deux comptes différents, enchérir.
4. Laisser le chrono atteindre 0 sur tous les objets séquentiellement → vérifier que les soldes diminuent dans phpMyAdmin et que `start.ended = 1` à la fin.
5. Vérifier que **l'overlay apparaît** côté user et côté admin avec la liste des gagnants. Pour le user qui a gagné un objet, son nom est highlighté.
6. Cliquer sur le `×` → l'overlay disparaît et ne se ré-ouvre pas en boucle.
7. Cliquer "Reset" côté admin → les soldes reviennent à 180000, les prix aux `prix_initial`, `start=0, ended=0, obj=1, chrono=30`. L'overlay disparaît côté admin et côté user (au prochain poll).
8. Re-cliquer "Start" → nouveau cycle utilisable.
