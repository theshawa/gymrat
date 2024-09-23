<?php

$pageTitle = null;
$pageStyles = [];
if (isset($pageConfig)) {
    $pageTitle = $pageConfig['title'] ?? null;
    $pageStyles = $pageConfig['styles'] ?? [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?  $pageTitle . " | "  : "" ?>GYMRAT ~ Staff</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/staff/styles/globals.css">
    <link rel="stylesheet" href="/staff/styles/components.css">
    <?php
    foreach ($pageStyles as $style) {
        echo "<link rel='stylesheet' href='$style'/>";
    }
    ?>
</head>

<body>