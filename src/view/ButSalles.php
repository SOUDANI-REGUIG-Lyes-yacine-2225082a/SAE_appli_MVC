<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salles Libres</title>
    <link rel="icon" type="image/png" href="../../_assets/images/favicon.png">
    <style>
        /* Styles pour les blocs carrés arrondis */
        .square {
            width: 200px;
            height: 200px;
            border-radius: 20px;
            border: 2px solid black;
            display: inline-block;
            margin: 20px; /* Ajustez la marge selon votre mise en page */
        }

        /* Style pour le bloc carré orange */
        .orange {
            background-color: orange;
        }

        /* Style pour le bloc carré bleu */
        .blue {
            background-color: blue;
        }
    </style>
</head>
<body>

<a href="index.php?action=roueDeChoix">
    <img id="return" src="../../_assets/images/return.png">
</a>

<img id="logo" src="../../_assets/images/logo.png" alt="Logo">

<!-- Bloc carré orange -->
<div class="square orange"></div>

<!-- Bloc carré bleu -->
<div class="square blue"></div>

</body>
</html>
