<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect_with_error_alert("Announcement ID is required.", "/staff/admin/announcements");
    exit;
}
$pageTitle = "Delete Announcement";
$pageStyles = ["../../admin.css"];
$sidebarActive = 5;

require_once "../../../../db/models/Announcement.php";
require_once "../../../../alerts/functions.php";

$announcement = new Announcement();
if (!isset($_SESSION['announcement'])) {    
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
    $_SESSION['announcement'] = serialize($announcement);
} else {
    $announcement = unserialize($_SESSION['announcement']);
}


$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/announcements/view/index.php?id=$id"
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <form action="delete_announcement.php" method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
            <?php require_once "../../../includes/menubar.php"; ?>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 10px; margin: 20px;">
            <div style="grid-column:1; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 5px 0;">Title</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->title ?></p>
                <h1 style="margin: 5px 0;">Message</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->message ?></p>
                <h1 style="margin: 5px 0;">Source</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->source ?></p>
                <h1 style="margin: 5px 0;">Send To</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->to_all ?></p>
                <h1 style="margin: 5px 0;">Created On</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->created_at->format('Y-m-d H:i:s') ?></p>
                <h1 style="margin: 5px 0;">Valid Till</h1>
                <p style="margin-bottom: 20px;"><?= $announcement->valid_till->format('Y-m-d H:i:s') ?></p>
            </div>
            <div style="grid-column:2; align-self: start; justify-self: start; text-align: left;">
                <h1>Are you sure you want to delete this announcement? </h1>
                <p style="margin: 5px 0;">This action is irreversible and cannot be undone.</p>
                <button type="submit" style="margin: 20px 0px; width: 200px; height: 40px;" class="staff-button destructive">
                    Delete Announcement
                </button>
            </div>
        </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
