<?php
    use app\modules\game\models\GameTypes;
?>

$(() => {
    const gameTypes = [
        <?php foreach (GameTypes::GAME_TYPE_MAP as  $gameTypeName => $gameTypeClass) : ?>
            '<?= $gameTypeName ?>',
        <?php endforeach; ?>
    ];
});

