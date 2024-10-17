<?php

$showImage = false;
$showExtend = false;
$extendTo = null;
$cards = null;

// FOR TESTING
//$goBackTo = "/staff/wnmp/index.php";

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
}

?>

<div class="info-card-grid">
    <?php foreach ($cards as $card): ?>
        <div class="info-card">
            <?php if ($showImage): ?>
                <div class="info-card-img">
                    <img src="<?= $card['img'] ?>" alt="<?= $card['title'] ?>">
                </div>
            <?php endif; ?>
            <div class="info-card-desc">
                <h2><?= $card['title'] ?></h2>
                <p><?= $card['description'] ?></p>
                <div class="info-card-ext">
                    <a href="<?= $extendTo ?>">
                        View More
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .info-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
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
        justify-content: space-between;
        align-items: center;
    }
    .info-card-img {
        width: 30%;
    }
    .info-card-desc {
        display: flex;
        flex-direction: column;
    }
    .info-card-ext {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }
</style>