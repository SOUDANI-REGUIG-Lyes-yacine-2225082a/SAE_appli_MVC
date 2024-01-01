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
    <style>
        /* Vos styles CSS */
        body {
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px; /* Augmentation de l'espace entre les cartes */
            padding: 20px;
        }

        .flip-card {
            background-color: transparent;
            width: 180px; /* Réduction de la taille des cartes */
            height: 110px; /* Réduction de la taille des cartes */
            perspective: 1000px;
            position: relative;
            margin-bottom: 20px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front {
            background-color: #49abf1;
            color: black;
            font-size: 12px; /* Réduction de la taille du texte */
            padding: 10px; /* Ajout de marge interne */
            cursor: pointer; /* Curseur pointer */
            border-radius: 10px; /* Coins arrondis */
        }

        .flip-card-back {
            background-color: #fca808;
            color: #000000;
            transform: rotateY(180deg);
            font-size: 12px; /* Réduction de la taille du texte */
            padding: 10px; /* Ajout de marge interne */
            border-radius: 10px; /* Coins arrondis */
        }

        /* Style des blocs orange et bleus */
        .orange-blocks,
        .blue-blocks {
            display: none; /* Initialement cachés */
            width: 1000px;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 250px; /* Descendus */
            left: 50%;
            transform: translateX(-50%);
            padding: 30px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5); /* Lueur blanche */
        }

        .orange-block,
        .blue-block {
            width: 600px; /* Largeur conservée */
            height: 320px; /* Hauteur conservée */
            margin: 25px;
            border-radius: 20px; /* Coins arrondis */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Lueur noire */
        }

        .orange-block {
            background-color: orange;
            box-shadow: 0 0 10px rgba(255, 140, 0, 0.5); /* Lueur orange */
        }

        .blue-block {
            background-color: #49abf1;
            box-shadow: 0 0 10px rgba(73, 171, 241, 0.5); /* Lueur bleue */
        }
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

<script>
    function toggleBlocks(showId, hideId) {
        var showBlocks = document.getElementById(showId);
        var hideBlocks = document.getElementById(hideId);

        if (showBlocks.style.display === 'none') {
            showBlocks.style.display = 'flex';
            hideBlocks.style.display = 'none';
        } else {
            showBlocks.style.display = 'none';
        }
    }
</script>

</body>
</html>
