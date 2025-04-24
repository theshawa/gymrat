<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect_with_error_alert("Announcement ID is required.", "/staff/admin/announcements");
    exit;
}
$pageTitle = "Edit Announcement";
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
    "title" => "Edit Announcement",
    "showBack" => true,
    "goBackTo" => "/staff/admin/announcements/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "edit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <form action="edit_announcement.php" method="POST">
            <?php require_once "../../../includes/menubar.php"; ?>
            <div style="padding: 20px; display: grid; gap: 20px;">
                <div style="margin-bottom: 10px;">
                    <h2><label for="title">Title</label></h2>
                    <input type="text" id="title" name="title" class="staff-input-primary staff-input-long"
                        value="<?= $announcement->title ?>" required>
                </div>
                <div style="margin-bottom: 10px;">
                    <h2><label for="message">Message</label></h2>
                    <textarea id="message" name="message" rows="8" 
                    class="staff-input-primary staff-input-long" style="width: 60%;" required><?= $announcement->message ?></textarea>
                </div>
                <div style="margin-bottom: 10px;">
                    <h2><label for="valid_till">Valid Till</label></h2>
                    <input type="datetime-local" id="valid_till" name="valid_till" class="staff-input-primary staff-input-long"
                        value="<?= $announcement->valid_till->format('Y-m-d\TH:i') ?>" required>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
