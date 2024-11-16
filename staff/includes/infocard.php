<?php

$showImage = false;
$showExtend = false;
$extendTo = null;
$cards = null;
$isCardInList = false; // REMOVE WHEN FULLY TURNED TO CRUD
$gridColumns = 2;
$defaultImage = "../assets/infoCardDefault.png"; // FIX DEFAULT IMAGE

if (isset($infoCardConfig)) {
    if (isset($infoCardConfig['showImage'])) {
        $showImage = $infoCardConfig['showImage'];
    }
    if (isset($infoCardConfig['showExtend'])) {
        $showExtend = $infoCardConfig['showExtend'];
    }
    if (isset($infoCardConfig['extendTo'])) {
        $extendTo = $infoCardConfig['extendTo'];
    }
    if (isset($infoCardConfig['cards'])) {
        $cards = $infoCardConfig['cards'];
    }
    if (isset($infoCardConfig['gridColumns'])) {
        $gridColumns = $infoCardConfig['gridColumns'];
    }
    if (isset($infoCardConfig['isCardInList'])) {
        $isCardInList = $infoCardConfig['isCardInList'];
    }
}

if (!$isCardInList) {
    $newCards = [];
    foreach ($cards as $card) {
        $newCards[] = [
            "id" => $card->id,
            "title" => $card->name,
            "description" => $card->description,
            "image" => $card->image ?: null
        ];
    }
    $cards = $newCards;
}

?>

<div class="info-card-grid">
    <?php foreach ($cards as $card): ?>
        <div class="info-card">
            <?php if ($showImage): ?>
                <div class="info-card-img">
                    <?php if ($card['image']): ?>
                        <img src="<?= $card['image'] ?>" alt="<?= $card['title'] ?>">
                    <?php else: ?>
                        <img src="<?= $defaultImage ?>" alt="Default">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="info-card-desc">
                <h2><?= $card['title'] ?></h2>
                <p><?= $card['description'] ?></p>
                <?php if ($showExtend): ?>
                    <div class="info-card-ext">
                        <a href="<?= $extendTo ?>?id=<?= $card['id'] ?>">
                            View More
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .info-card-grid {
        display: grid;
        grid-template-columns: repeat(<?= $gridColumns ?>, 1fr);
        gap: 20px;
    }
    .info-card {
        background-color: var(--color-zinc-100);
        border: 2px solid var(--color-zinc-200);
        width: 100%;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        flex-direction: row;
        /*justify-content: space-between;*/
        align-items: center;
    }
    .info-card-img {
        width: 30%;
    }
    .info-card-desc {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    .info-card-ext {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }
</style>