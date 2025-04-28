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
$sidebarActive = 10;
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
        <form action="edit_settings.php" method="POST" enctype="multipart/form-data">
            <?php require_once "../../../includes/menubar.php"; ?>
            <div style="padding: 5px 10px;">
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-gym-name">Gym Name</label></h2>
                    <input type="text" id="edit-gym-name" name="gym_name"
                        class="staff-input-primary staff-input-long" value="<?= $settings->gym_name ?>"
                        placeholder="Enter gym name">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-gym-desc">Gym Description</label></h2>
                    <textarea id="edit-gym-desc" name="gym_desc" class="staff-input-primary staff-input-long"
                        placeholder="Enter gym description" rows="8" style="width: 60%;" ><?= $settings->gym_desc ?></textarea>
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-gym-address">Gym Address</label></h2>
                    <input type="text" id="edit-gym-address" name="gym_address"
                        class="staff-input-primary staff-input-long" value="<?= $settings->gym_address ?>"
                        placeholder="Enter gym address">
                </div>
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
                    <h2><label for="edit-workout-expiry">Workout Session Expiration Time (Hours)</label></h2>
                    <input type="text" id="edit-workout-expiry" name="workout_session_expiry"
                        class="staff-input-primary staff-input-long" value="<?= $settings->workout_session_expiry ?>"
                        pattern="\d+" title="Please enter a non-negative integer">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-max-capacity">Max Capacity</label></h2>
                    <input type="text" id="edit-max-capacity" name="max_capacity"
                        class="staff-input-primary staff-input-long" value="<?= $settings->max_capacity ?>"
                        pattern="\d+" title="Please enter a non-negative integer">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-min-workout-time">Minimum Workout Time (Hours) </label></h2>
                    <input type="text" id="edit-min-workout-time" name="min_workout_time"
                        class="staff-input-primary staff-input-long" value="<?= $settings->min_workout_time ?>"
                        pattern="\d+" title="Please enter a non-negative integer">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-gym-banner">Gym Banner</label></h2>
                    <input type="file" id="edit-gym-banner" name="gym_banner" class="staff-input-primary">
                </div>
                <div style="margin-bottom: 10px">
                    <h2><label for="edit-show-widgets">Show Widgets</label></h2>
                    <select id="edit-show-widgets" name="show_widgets" class="staff-input-primary staff-input-long">
                        <option value="1" <?= $settings->show_widgets ? "selected" : "" ?>>Yes</option>
                        <option value="0" <?= !$settings->show_widgets ? "selected" : "" ?>>No</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
