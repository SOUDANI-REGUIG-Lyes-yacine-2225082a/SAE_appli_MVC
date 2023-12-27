<!-- le layout contient des éléements qui vont apparaitre plusieurs fois dans differentes, pages ont pourrait
mmettre le logo mais jai la flemme, je rajoute juste pour les edt-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps</title>

    <link rel="stylesheet" href="../../_assets/styles/schedule.css">

</head>
<body>

<!-- Oui, c'est normal si ya une erreur, enleve pas, c'est grace à cette echo qu'on affiche les edt.
 Tu vas dans le fihier SheduleView.php, dedans ya la déclaration de $content, elle est pas là c'est pour ca ya une erreur-->
<?php
    echo $content;
?>

<!-- On peut rajouter le footer et le header dans ce fichier et l'appelez dans les fichers. -->
</body>
</html>
