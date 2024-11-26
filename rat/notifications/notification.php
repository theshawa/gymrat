<?php

$id = htmlspecialchars($_GET["id"]);

if (!isset($id)) {
    header("Location: /notifications");
    exit();
}

$pageConfig = [
    "title" => "Notification",
    "styles" => ["./notifications.css"],
    "titlebar" => [
        "back_url" => "/rat/notifications/index.php",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <h1>Temporary Gym Closure Due to Bad Weather</h1>
    <p class="time">22.9.2024 09:10 AM</p>
    <p class="paragraph" style="margin-top: 20px;">
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rerum, facilis dolores dolor exercitationem animi maxime architecto, iste porro inventore doloribus soluta error fugit eius ea! Dolores itaque consectetur excepturi officia.
    </p>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>