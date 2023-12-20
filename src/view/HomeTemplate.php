<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="icon" type="image/png" href="../../_assets/images/favicon.png">
    <link rel="stylesheet" href="../../_assets/styles/PageCommune.css">
    <link rel="stylesheet" href="../../_assets/styles/AcceuilStyle.css">
</head>
<body>
<script src="../../_assets/scripts/toggleImage.js"></script>
<script src="../../_assets/scripts/HideText.js"></script>
<script src="../../_assets/scripts/main.js"></script>

<div class="container">
    <div class="wrapper">
        <svg width="1200" height="200">
            <text x="60%" y="40%" dy=".35em" text-anchor="middle" font-size="20" id="displayText">
                Bienvenue au département
                <tspan x="50%" dy="1.2em">Informatique</tspan>
            </text>
        </svg>
    </div>
    <div class="top-right-image">
    </div>
    <div class="image-button">
        <a href="index.php?action=roueDeChoix">
            <img src="../../_assets/images/Home_button.png" width="300" height="225" id="homeButton" onclick="changeText()">
        </a>
    </div>
</div>
<img id="logo" src="../../_assets/images/logo.png" alt="Logo">
</body>
</html>
