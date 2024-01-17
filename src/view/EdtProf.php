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
<section id="liste-professeurs">
    <h2>Liste des Professeurs</h2>
    <?php if (empty($professeurs)): ?>
        <p>Aucun professeur n'est enregistré.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($professeurs as $profName): ?>
                <li>
                    <a href="index.php?group=<?= urlencode($profName) ?>">
                        <?= htmlspecialchars($profName) ?>
                    </a>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>