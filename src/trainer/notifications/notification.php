<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

$id = htmlspecialchars($_GET["id"]);
$type = isset($_GET['type']) ? (htmlspecialchars($_GET['type']) === "announcement" ? "announcement" : "notification") : "notification";

require_once "../../alerts/functions.php";
require_once "../../db/models/Notification.php";
$notification;

if ($type === "announcement") {
    require_once "../../db/models/Announcement.php";
    $notification = new Announcement();
} else {
    $notification = new Notification();
}
try {
    $notification->fill([
        "id" => (int)$id,
    ]);
    $notification->get_by_id();
    if ($type === "notification") {
        $notification->mark_as_read();
    }
} catch (\Throwable $th) {
    die("Failed to load notification due to error: " . $th->getMessage());
}

if (!isset($id)) {
    header("Location: ./");
    exit();
}

$pageConfig = [
    "title" => $type == "announcement" ? "Announcement" : "Notification",
    "styles" => ["./notifications.css"],
    "titlebar" => [
        "back_url" => "./",
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <h1><?= $notification->title ?></h1>
    <?php if ($notification->source): ?>
        <p class="time">
            From <?= $type == "announcement" ? ($notification->source != "admin" ? "trainer" : "admin") : $notification->source ?>
        </p>
    <?php endif; ?>
    <p class="time">
        At <?= $notification->created_at->format("Y-m-d h:i a") ?>
    </p>
    <p class="notifcation-content" style="margin-top: 10px;">
        <?= $notification->message ?>
    </p>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>