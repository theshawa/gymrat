<?php

$title = "MenuBar";
$showBack = false;
$goBackTo = null;
$options = null; // options[type]s are primary, secondary, destructive
$useLink = false; // If you want to use link
$useButton = false; // If you want to use button

// FOR TESTING
//$goBackTo = "/staff/wnmp/index.php";

if (isset($menuBarConfig)) {
    if (isset($menuBarConfig['title'])) {
        $title = $menuBarConfig['title'];
    }
    if (isset($menuBarConfig['showBack'])) {
        $showBack = $menuBarConfig['showBack'];
    }
    if (isset($menuBarConfig['goBackTo'])) {
        $goBackTo = $menuBarConfig['goBackTo'];
    }
    if (isset($menuBarConfig['options'])) {
        $options = $menuBarConfig['options'];
    }
    if (isset($menuBarConfig['useLink'])) {
        $useLink = $menuBarConfig['useLink'];
    }
    if (isset($menuBarConfig['useButton'])) {
        $useButton = $menuBarConfig['useButton'];
    }
}


?>
<div class="menu-bar">
    <div style="display: flex; align-items: center;">
        <?php if ($showBack && $goBackTo): ?>
            <a href="/staff/includes/menubar_goback.php?goBackTo=<?= $goBackTo ?>" class="menu-bar-back">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
            </a>
        <?php endif; ?>
        <h1 class="alt"><?= $title ?></h1>
    </div>
    <div style="display: flex; align-items: center; gap: 6px;">
        <?php if ($useLink && $options): ?>
            <?php foreach ($options as $option): ?>
                <a href="<?= $option['href'] ?>" class="staff-button <?= empty($option['type']) ? 'primary' : $option['type'] ?>">
                    <?php if (!empty($option['setAlert']) && $option['setAlert']): ?>
                        <span class="staff-alert-red-dot"></span>
                    <?php endif; ?>
                    &nbsp;<?= $option['title'] ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($useButton && $options): ?>
            <?php foreach ($options as $option): ?>
                <button class="staff-button <?= empty($option['type']) ? 'primary' : $option['type'] ?>"
                    <?= !empty($option['buttonFunction']) ? 'onclick="' . $option['buttonFunction'] . '()"' : '' ?>
                        type="<?= empty($option['buttonType']) ? 'button' : $option['buttonType'] ?>"
                    <?= !empty($option['formAction']) ? 'formaction="' . $option['formAction'] .'"' : '' ?>
                    <?= !empty($option['buttonName']) ? 'name="' . $option['buttonName'] . '"' : '' ?>
                    <?= !empty($option['buttonValue']) ? 'value="' . $option['buttonValue'] . '"' : '' ?>
                >
                <?php if (!empty($option['setAlert']) && $option['setAlert']): ?>
                    <span class="staff-alert-red-dot"></span>
                <?php endif; ?>
                &nbsp;<?= $option['title'] ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .menu-bar {
        display: flex;
        align-items: center;
        margin: 10px 10px 30px 10px;
        justify-content: space-between;
    }
    .menu-bar-back {
        border: none;
        background-color: transparent;
    }
    .menu-bar h1 {
        margin-left: 10px;
    }

</style>