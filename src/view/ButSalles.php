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
</head>
<body>

<!-- Salle informatique -->
<div class="orange-box" onclick="toggleBlocks('orangeBlocks', 'blueBlocks')">
    <h2>Salle Informatique</h2>
</div>

<!-- Salle de TP -->
<div class="blue-box" onclick="toggleBlocks('blueBlocks', 'orangeBlocks')">
    <h2>Salle de TP</h2>
</div>

<!-- Blocs orange -->
<div class="orange-blocks" id="orangeBlocks">
    <?php if (isset($availableRooms)): ?>
        <?php foreach ($availableRooms as $room): ?>
            <?php if (strpos($room, 'TP') !== false): ?>
                <div class="orange-block"><?php echo htmlspecialchars($room); ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Blocs bleus -->
<div class="blue-blocks" id="blueBlocks">
    <?php if (isset($availableRooms)): ?>
        <?php foreach ($availableRooms as $room): ?>
            <?php if (strpos($room, 'TD') !== false): ?>
                <div class="blue-block"><?php echo htmlspecialchars($room); ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="image-button">
    <a href="index.php?group=roueDeChoix">
        <img id="return" src="../../_assets/images/return.png">
    </a>
</div>

<script src="../../_assets/scripts/main.js"></script>
</body>
</html>
