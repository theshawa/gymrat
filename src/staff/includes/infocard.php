<?php

$defaultName = "";
$showImage = false;
$showExtend = false;
$showCreatedAt = true;
$extendTo = null;
$cards = null;
$isCardInList = false; // REMOVE WHEN FULLY TURNED TO CRUD
$gridColumns = 2;
$uploadsPath = "../../../uploads/";
$defaultImage = "default-images/infoCardDefault.png";

if (isset($infoCardConfig)) {
    if (isset($infoCardConfig['defaultName'])) {
        $defaultName = $infoCardConfig['defaultName'];
    }
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
    if (isset($infoCardConfig['showCreatedAt'])) {
        $showCreatedAt = $infoCardConfig['showCreatedAt'];
    }
}

if (!$isCardInList) {
    $newCards = [];
    foreach ($cards as $card) {
       if (isset($card->description)) {
           $description = $card->description;
           $wordLimit = 15;

           $descriptionWordsArray = explode(' ', $description);
           $descriptionFirstSegment = array_slice($descriptionWordsArray, 0, $wordLimit);
           $card->description = implode(' ', $descriptionFirstSegment) . (count($descriptionWordsArray) > $wordLimit ? '...' : '');
       }

        $newCards[] = [
            "id" => $card->id,
            "title" => $card->name ?? $defaultName . " #" . $card->id,
            "description" => $card->description ?? "",
            "image" => $card->image ?? null,
            "created_at" => $card->created_at ? $card->created_at->format('Y-m-d H:i:s') : null
        ];
    }
    $cards = $newCards;
}

?>

<div class="info-card-grid">
    <?php foreach ($cards as $card): ?>
        <div class="info-card">
            <?php if ($showImage): ?>
                <div>
                    <?php if ($card['image']): ?>
                        <img src="<?= $uploadsPath . $card['image'] ?>" alt="<?= $card['title'] ?>" class="info-card-img">
                    <?php else: ?>
                        <img src="<?= $uploadsPath . $defaultImage ?>" alt="Default" class="info-card-img">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="info-card-desc">
                <h2><?= ($card['title'] ?? $card['name']) ?></h2>
                <p><?= (isset($card['created_at']) && ($showCreatedAt)) ? "[ " . $card['created_at'] . " ] " : "" ?><?= $card['description'] ?></p>
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
        width: 100px; 
        height: 100px; 
        object-fit: cover;
        margin-right: 20px;
        border-radius: 10px;
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