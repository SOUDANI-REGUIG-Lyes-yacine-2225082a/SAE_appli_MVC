<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" type="image/png" href="../../_assets/images/favicon.png">
    <link rel="stylesheet" href="../../_assets/styles/PageCommune.css">
    <link rel="stylesheet" href="../../_assets/styles/AcceuilStyle.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            font-family: 'Russo One', sans-serif;
        }

        .top-part {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .bottom-part {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .image-button {
            text-align: center;
        }

        #Acceuil_Up {
            width: 700px;
            height: 300px;
        }

        #homeButton {
            width: 300px;
            height: 225px;
        }

        .logo {
            position: fixed;
            top: -30px;
            right: 2060px;
            transform: scale(0.25);
            z-index: 5;
        }

        .image-container {
            position: relative;
            text-align: center;
        }


    </style>
</head>
<body>
<div class="top-part">
    <img src="../../_assets/images/Acceuil.png" width="790" height="300" id="Acceuil_Up">
</div>
<div class="bottom-part">
    <div class="container">
        <div class="top-right-image"></div>
        <div class="image-button">
            <a href="index.php?group=roueDeChoix">
                <img src="../../_assets/images/Home_button.png" width="300" height="225" id="homeButton" onclick="changeText()">
            </a>
        </div>
    </div>
    <img id="logo" src="../../_assets/images/logo.png" alt="Logo">
</div>
</body>
</html>
