<?php

session_start();

$pageTitle = null;
$pageStyles = [];
if (isset($pageConfig)) {
    $pageTitle = $pageConfig['title'] ?? null;
    $pageStyles = $pageConfig['styles'] ?? [];
}

$alert = $_GET['alert'] ?? null;
?>
<?php if ($alert): ?>
    <script>
        alert("<?= $alert ?>");
    </script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYMRAT<?php echo $pageTitle ? " | " . $pageTitle : "" ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/rat/styles/globals.css">
    <link rel="stylesheet" href="/rat/styles/components.css">
    <?php
    foreach ($pageStyles as $style) {
        echo "<link rel='stylesheet' href='$style'/>";
    }
    ?>
</head>

<body>