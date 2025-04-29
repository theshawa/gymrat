<?php

$defaultName = "";
$concatName = false;
$showImage = false;
$useAvatar = false;
$showExtend = false;
$showDescription = true;
$showCreatedAt = true;
$extendTo = null;
$cards = null;
$isCardInList = false; // REMOVE WHEN FULLY TURNED TO CRUD
$gridColumns = 2;
$uploadsPath = "/uploads/";
$defaultImage = "default-images/infoCardDefault.png";

if (isset($infoCardConfig)) {
    $defaultName = $infoCardConfig['defaultName'] ?? $defaultName;
    $showImage = $infoCardConfig['showImage'] ?? $showImage;
    $showExtend = $infoCardConfig['showExtend'] ?? $showExtend;
    $showDescription = $infoCardConfig['showDescription'] ?? $showDescription;
    $extendTo = $infoCardConfig['extendTo'] ?? $extendTo;
    $cards = $infoCardConfig['cards'] ?? $cards;
    $gridColumns = $infoCardConfig['gridColumns'] ?? $gridColumns;
    $isCardInList = $infoCardConfig['isCardInList'] ?? $isCardInList;
    $showCreatedAt = $infoCardConfig['showCreatedAt'] ?? $showCreatedAt;
    $concatName = $infoCardConfig['concatName'] ?? $concatName;
    $useAvatar = $infoCardConfig['useAvatar'] ?? $useAvatar;
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
            "title" => ($concatName) ?
                $card->fname . " " . $card->lname :
                $card->name ?? $defaultName . " No. " . $card->id,
            "description" => ($showDescription) ? ($card->description ?? "") : "",
            "image" => ($useAvatar) ? $card->avatar : ($card->image ?? null),
            "created_at" => ($showCreatedAt && isset($card->created_at)) ? $card->created_at->format('Y-m-d H:i:s') : null
        ];
    }
    $cards = $newCards;
}

?>

<div class="info-card-grid">
    <?php foreach ($cards as $card): ?>
        <a class="info-card" href="<?= $extendTo ?>?id=<?= $card['id'] ?>">
            <?php if ($showImage): ?>
                <div>
                    <?php if ($showImage && $card["image"]): ?>
                        <img src="<?= $uploadsPath . $card["image"] ?>" alt="<?= $card['title'] ?>" class="info-card-img">
                    <?php else: ?>
                        <img src="<?= $uploadsPath . $defaultImage ?>" alt="Default" class="info-card-img">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="info-card-desc">
                <h2><?= ($card['title'] ?? $card['name']) ?></h2>
                <p><?= (isset($card['created_at']) && ($showCreatedAt)) ? "[ " . $card['created_at'] . " ] " : "" ?><?php
                                                                                                                    try {
                                                                                                                        $description = json_decode($card['description'], true);
                                                                                                                        if (!is_array($description)) {
                                                                                                                            throw new Exception("Invalid description format");
                                                                                                                        }
                                                                                                                        foreach ($description as $key => $value) {
                                                                                                                            if (is_array($value)) {
                                                                                                                                echo $key . ": " . implode(", ", $value) . "<br>";
                                                                                                                            } else {
                                                                                                                                echo $key . ": " . $value . "<br>";
                                                                                                                            }
                                                                                                                        }
                                                                                                                    } catch (\Throwable $th) {
                                                                                                                        echo $card['description'];
                                                                                                                    }
                                                                                                                    ?></p>
                <?php if ($showExtend): ?>
                    <!-- <div class="info-card-ext">
                        <a href="<?= $extendTo ?>?id=<?= $card['id'] ?>">
                            View More
                        </a>
                    </div> -->
                <?php endif; ?>
            </div>
        </a>
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
        border: 1px solid var(--color-zinc-200);
        width: 100%;
        border-radius: 20px;
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
        border-radius: 20px;
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