<?php

$title = "MenuBar";
$showBack = false;
$goBackTo = null;
$showOptions = true;
$options = null; // options[type]s are primary, secondary, destructive

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
        $backTo = $menuBarConfig['goBackTo'];
    }
    if (isset($menuBarConfig['showOptions'])) {
        $showOptions = $menuBarConfig['showOptions'];
    }
    if (isset($menuBarConfig['options'])) {
        $options = $menuBarConfig['options'];
    }
}

?>
<div class="top-bar">
    <div style="display: flex; align-items: center;">
        <?php if ($showBack && $goBackTo): ?>
            <a href="<?= $goBackTo ?>" class="top-bar-back">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
            </a>
        <?php endif; ?>
        <h1><?= $title ?></h1>
    </div>
    <div>
        <?php if ($showOptions && $options): ?>
            <?php foreach ($options as $option): ?>
                <a href="<?= $option['href'] ?>" class="option <?= empty($option['type']) ? 'primary' : $option['type'] ?>">
                    <?= $option['title'] ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .top-bar {
        display: flex;
        align-items: center;
        margin: 10px 10px 30px 10px;
        justify-content: space-between;
    }
    .top-bar-back {
        border: none;
        background-color: transparent;
    }
    .top-bar h1 {
        margin-left: 10px;
    }
    .option {
        height: 30px;
        padding: 0 20px;
        font-weight: 500;
        border-radius: 6px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
    }
    .option.primary {
        color: var(--color-zinc-600);
        background-color: var(--color-zinc-100);
        border: 1px solid var(--color-zinc-200);
    }
    .option.secondary {
        background-color: var(--color-zinc-800);
        color: var(--color-zinc-50);
        border-color: var(--color-zinc-950);
    }
    .option.destructive {
        background-color: var(--color-red-light);
        color: var(--color-zinc-50);
        border-color: var(--color-red);
    }
</style>