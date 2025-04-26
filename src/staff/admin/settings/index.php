<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Settings";
$sidebarActive = 9;
$pageStyles = ["../admin.css"];
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Edit Settings", "href" => "/staff/admin/settings/edit/index.php", "type" => "secondary"]
    ]
];

require_once "../../../db/models/Settings.php"; 
require_once "../../../alerts/functions.php";

$settings = new Settings();
try {
    $settings->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch settings: " . $e->getMessage(), "/staff/admin");
    exit;
}

require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div style="margin: 0 20px;">
            <div class="settings-list-item">
                <h1>Gym Name</h1>
                <p><?= $settings->gym_name ?? "Not specified" ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Gym Description</h1>
                <p><?= $settings->gym_desc ?? "Not specified" ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Gym Address</h1>
                <p><?= $settings->gym_address ?? "Not specified" ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Contact Email</h1>
                <p><?= $settings->contact_email ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Contact Phone</h1>
                <p><?= $settings->contact_phone ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Workout Session Expiration Time</h1>
                <p><?= $settings->workout_session_expiry ?> hours</p>
            </div>
            <div class="settings-list-item">
                <h1>Max Capacity</h1>
                <p><?= $settings->max_capacity ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Min Workout Time</h1>
                <p><?= $settings->min_workout_time ?> hours</p>
            </div>
            <div class="settings-list-item">
                <h1>Gym Banner</h1>
                <?php if ($settings->gym_banner): ?>
                    <img src="../../../../uploads/<?= $settings->gym_banner ?>" alt="Gym Banner"
                    style="width: 60%; height: auto; border-radius: 20px; margin-top: 10px;">
                <?php else: ?>
                    <p>No banner uploaded</p>
                <?php endif; ?>
            </div>
            <div class="settings-list-item">
                <h1>Show Widgets</h1>
                <p><?= $settings->show_widgets ? "Yes" : "No" ?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>