<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salles Libres</title>
    <link rel="icon" type="image/png" href="../../_assets/images/favicon.png">
    <link rel="stylesheet" href="../../_assets/styles/ButS.css">
    <link rel="stylesheet" href="../../_assets/styles/PageCommune.css">
    <link rel="stylesheet" href="styles.css">
    <script src="../../_assets/scripts/main.js"></script>
    <style>
        /* Votre CSS ici */
        /* ... (insérez votre CSS pour les cartes flip ici) */
    </style>
</head>
<body>

<!-- Première carte - Salle de TD normaux -->
<div class="flip-card" onclick="toggleBlocks('orangeBlocks', 'blueBlocks')">
    <div class="flip-card-inner">
        <div class="flip-card-front">
            <h2>Salles de TD</h2>
        </div>
        <div class="flip-card-back">
            <p>Ce sont des salles de cours standard pour les séances de travaux dirigés.</p>
        </div>
    </div>
</div>
<!-- Fin de la première carte -->

<!-- Deuxième carte - Salles Machines -->
<div class="flip-card" onclick="toggleBlocks('blueBlocks', 'orangeBlocks')">
    <div class="flip-card-inner">
        <div class="flip-card-front">
            <h2>Salles Machines</h2>
        </div>
        <div class="flip-card-back">
            <p>Ce sont des salles équipées de machines pour des travaux pratiques ou spécifiques.</p>
        </div>
    </div>
</div>

<!-- Blocs orange -->
<div class="orange-blocks" id="orangeBlocks">
    <div class="orange-block"></div>
    <div class="orange-block"></div>
    <div class="orange-block"></div>
</div>

<!-- Blocs bleus -->
<div class="blue-blocks" id="blueBlocks">
    <div class="blue-block"></div>
    <div class="blue-block"></div>
    <div class="blue-block"></div>
</div>

</body>
</html>
