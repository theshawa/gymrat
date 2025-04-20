<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../db/models/Settings.php";
require_once "../../../../alerts/functions.php";

$settings = new Settings();
if (isset($_SESSION['settings'])) {
    $settings = unserialize($_SESSION['settings']);
} else {
    try {
        $settings->get_all();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch settings: " . $e->getMessage(), "/staff/admin/settings");
        exit;
    }
    $_SESSION['settings'] = serialize($settings);
}


$pageTitle = "Edit Settings";
$sidebarActive = 9;
$pageStyles = ["../../admin.css"];
$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/settings/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", 
         "buttonName" => "action", "buttonValue" => "edit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", 
        "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <form action="edit_settings.php" method="POST">
            <?php require_once "../../../includes/menubar.php"; ?>
            <div style="padding: 5px 10px;">
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-contact-email">Contact Email</label></h2>
                    <input type="email" id="edit-contact-email" name="contact_email"
                        class="staff-input-primary staff-input-long" value="<?= $settings->contact_email ?>"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-contact-phone">Contact Phone</label></h2>
                    <input type="text" id="edit-contact-phone" name="contact_phone"
                        class="staff-input-primary staff-input-long" value="<?= $settings->contact_phone ?>"
                        pattern="\d{10}" title="Please enter a 10-digit phone number">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-workout-expiry">Workout Session Expiration Time</label></h2>
                    <input type="text" id="edit-workout-expiry" name="workout_session_expiry"
                        class="staff-input-primary staff-input-long" value="<?= $settings->workout_session_expiry ?>"
                        pattern="\d+" title="Please enter a non-negative integer">
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
