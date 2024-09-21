<?php

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: /notifications");
    exit();
}

$pageConfig = [
    "title" => "Notification",
    "styles" => ["./notification.css"],
    "titlebar" => [
        "back_url" => "/notifications/index.php",
    ],
];

include_once "../includes/header.php";
include_once "../includes/titlebar.php";
?>

<main>
    <h1>Temporary Gym Closure Due to Bad Weather</h1>
    <p class="paragraph" style="margin-top: 20px;">
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rerum, facilis dolores dolor exercitationem animi maxime architecto, iste porro inventore doloribus soluta error fugit eius ea! Dolores itaque consectetur excepturi officia.
    </p>
</main>

<?php include_once "../includes/navbar.php" ?>
<?php include_once "../includes/footer.php" ?>