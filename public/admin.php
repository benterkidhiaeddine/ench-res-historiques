<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel</title>
    <link rel="stylesheet" href="assets/css/styleadmin.css">
    <script src="assets/js/scriptadmin.js" defer></script>
</head>
<body>
    <button id="start">Start</button>
    <button id="reset">Reset</button>

    <div id="resultsOverlay" hidden>
        <div id="resultsBox">
            <button id="resultsClose" type="button">×</button>
            <h1>Enchères terminées</h1>
            <ul id="resultsList"></ul>
        </div>
    </div>
</body>
</html>