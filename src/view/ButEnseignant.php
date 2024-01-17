<?php if (!empty($message)): ?>
    <div class="alert">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs</title>
    <link rel="stylesheet" href="../../_assets/styles/ButEnseignant.css">
</head>
<body>

<section id="ajout-professeur">
    <p>Debug ID: <?=uniqid()?></p>
    <h1>Ajouter un Professeur</h1>
    <form action="index.php?group=ButEnseignant" method="post">

    <div class="form-group">
            <label for="profName">Nom du Professeur:</label>
            <input type="text" id="profName" name="profName" required>
        </div>
        <div class="form-group">
            <label for="profId">ID du Professeur:</label>
            <input type="text" id="profId" name="profId" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Ajouter">
        </div>
    </form>
</section>

<section id="liste-professeurs">
    <h2>Liste des Professeurs</h2>
    <?php if (empty($professeurs)): ?>
        <p>Aucun professeur n'est enregistré.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($professeurs as $profName => $profId): ?>
                <li>
                    <a href="index.php?group=professeur&profName=<?= urlencode($profName) ?>">
                        <?= htmlspecialchars($profName) ?>
                    </a>
                    <!-- Bouton de suppression -->
                    <form action="index.php?group=supprimer" method="post" style="display: inline;">
                        <input type="hidden" name="profName" value="<?= htmlspecialchars($profName) ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</section>
</body>
</html>
