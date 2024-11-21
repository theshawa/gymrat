<?php


$subnavbarLinks = [];
$subnavbarActiveLink = 0;

if (isset($subnavbarConfig)) {
    $subnavbarLinks = $subnavbarConfig['links'] ?? [];
    $subnavbarActiveLink = $subnavbarConfig['active'] ?? 0;
}

?>

<nav class="subnavbar">
    <?php foreach ($subnavbarLinks as $i => $link): ?>
        <a href="<?= $link['href'] ?>" class="subnavbar-link <?= $subnavbarActiveLink === $i ? 'active' : '' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach; ?>
</nav>