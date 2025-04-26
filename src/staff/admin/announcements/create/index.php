<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Create Announcement";
$pageStyles = ["../../admin.css"];
$sidebarActive = 5;

require_once "../../../../db/models/Announcement.php";
require_once "../../../../alerts/functions.php";

$announcement = new Announcement();
if (!isset($_SESSION['announcement'])) {    
    try {
        $announcement->fill([]);;
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to initialize announcement: " . $e->getMessage(), "/staff/admin/announcements");
        exit;
    }
    $_SESSION['announcement'] = serialize($announcement);
} else {
    $announcement = unserialize($_SESSION['announcement']);
}

$menuBarConfig = [
    "title" => "Create Announcement",
    "showBack" => true,
    "goBackTo" => "/staff/admin/announcements",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "create", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <form action="create_announcement.php" method="POST">
            <?php require_once "../../../includes/menubar.php"; ?>
            <div style="padding: 20px; display: grid; gap: 20px;">
                <div style="margin-bottom: 10px;">
                    <h2><label for="title">Title</label></h2>
                    <input type="text" id="title" name="title" class="staff-input-primary staff-input-long" required>
                </div>
                <div style="margin-bottom: 10px;">
                    <h2><label for="message">Message</label></h2>
                    <textarea id="message" name="message" rows="8" 
                    class="staff-input-primary staff-input-long" style="width: 60%;" required></textarea>
                </div>
                <div style="margin-bottom: 10px;">
                    <h2><label for="message">Send To</label></h2>
                    <select id="send_to" name="send_to" class="staff-input-primary staff-input-long" required>
                        <option value="rats" <?= $announcement->to_all <= 1 ? 'selected' : '' ?>>Rats</option>
                        <option value="trainers" <?= $announcement->to_all == 2 ? 'selected' : '' ?>>Trainers</option>
                        <option value="rats+trainers" <?= $announcement->to_all == 3 ? 'selected' : '' ?>>Rats + Trainers</option>
                    </select>
                </div>
                <div style="margin-bottom: 10px;">
                    <h2><label for="valid_till">Valid Till</label></h2>
                    <input type="datetime-local" id="valid_till" name="valid_till" class="staff-input-primary staff-input-long" required>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
