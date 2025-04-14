<?php

$id = htmlspecialchars($_GET["id"]);

session_start();

require_once "../../alerts/functions.php";
require_once "../../db/models/Notification.php";
require_once "../../db/models/NotificationUser.php";
$notification;
try {
    $notification = new Notification();
    $notification->fill([
        "id" => $id,
    ]);
    $notification->get_by_id();
    $notification_user = new NotificationUser();
    $notification_user->fill([
        "user_id" => $_SESSION["auth"]["id"],
        "notification_id" => $notification->id,
        "user_type" => $_SESSION["auth"]["role"],
    ]);
    $notification_user->mark_as_read();
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
    <p class="time">
        <?= $notification->created_at->format("Y-m-d h:i") ?>
    </p>
    <p class="paragraph" style="margin-top: 20px;">
        <?= $notification->message ?>
    </p>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>