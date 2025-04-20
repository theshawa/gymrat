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
                <h1>Contact Email</h1>
                <p><?= $settings->contact_email ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Contact Phone</h1>
                <p><?= $settings->contact_phone ?></p>
            </div>
            <div class="settings-list-item">
                <h1>Workout Session Expiration Time</h1>
                <p><?= $settings->workout_session_expiry ?></p>
            </div>
            <!-- <?php foreach ($settings as $key => $value): ?>
                <?php if (!in_array($key, ['table', 'conn', 'id'])): ?>
                    <div class="settings-list-item">
                        <h1><?= htmlspecialchars($key) ?></h1>
                        <p><?= htmlspecialchars($value) ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?> -->
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>