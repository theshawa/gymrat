<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect_with_error_alert("Announcement ID is required.", "/staff/admin/announcements");
    exit;
}
$pageTitle = "View Announcement";
$pageStyles = ["../../admin.css"];
$sidebarActive = 5;

require_once "../../../../db/models/Announcement.php";
require_once "../../../../db/models/Trainer.php";
require_once "../../../../alerts/functions.php";

$announcement = new Announcement();
try {
    $announcement->id = $id;
    $announcement->get_by_id();
    if (!$announcement) {
        redirect_with_error_alert("Announcement not found.", "/staff/admin/announcements");
        exit;
    }
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch announcement: " . $e->getMessage(), "/staff/admin/announcements");
    exit;
}

if (preg_match('/^\d+$/', $announcement->source)) {
    $trainerModel = new Trainer();
    try {
        $announcement->source = $trainerModel->get_username_by_id($announcement->source);
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to retrieve trainer username: " . $e->getMessage();
    }
}

$currentDateTime = new DateTime();
$is_valid = $announcement->valid_till > $currentDateTime;


$menuBarConfig = [
    "title" => $announcement->title,
    "showBack" => true,
    "goBackTo" => "/staff/admin/announcements/index.php",
    "useLink" => $is_valid,
    "options" => ($is_valid) ? [
        ["title" => "Edit Announcement", "href" => "/staff/admin/announcements/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Announcement", "href" => "/staff/admin/announcements/delete/index.php?id=$id", "type" => "destructive"]
    ] : []
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 10px; margin: 20px;">
            <div style="grid-column:1; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 5px 0;">Message</h1>
                <p><?= $announcement->message ?></p>
            </div>
            <div style="grid-column:2; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 5px 0;">Source</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->source ?></p>
                <h1 style="margin: 5px 0;">Send To</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->to_all ?></p>
                <h1 style="margin: 5px 0;">Created On</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->created_at->format('Y-m-d H:i:s') ?></p>
                <h1 style="margin: 5px 0;">Valid Till</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->valid_till->format('Y-m-d H:i:s') ?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
