<?php

$id = htmlspecialchars($_GET["id"]);

session_start();

require_once "../../alerts/functions.php";
require_once "../../db/models/Notification.php";
$notification;
try {
    $notification = new Notification();
    $notification->fill([
        "id" => $id,
    ]);
    $notification->get_by_id();
    $notification->mark_as_read();
} catch (\Throwable $th) {
    redirect_with_error_alert("Failed to get notification due to error: " . $th->getMessage(), "./");
}

if (!isset($id)) {
    header("Location: ./");
    exit();
}

$pageConfig = [
    "title" => "Notification",
    "styles" => ["./notifications.css"],
    "titlebar" => [
        "back_url" => "./",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <h1><?= $notification->title ?></h1>
    <?php if ($notification->source): ?>
        <p class="time">
            From <?= $notification->source ?>
        </p>
    <?php endif; ?>
    <p class="time">
        At <?= $notification->created_at->format("Y-m-d h:i") ?>
    </p>
    <p class="paragraph" style="margin-top: 20px;">
        <?= $notification->message ?>
    </p>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>