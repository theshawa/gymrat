<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$setFilter = $_GET['filter'] ?? 0;
$pageTitle = "Anouncements";
$pageStyles = ["../admin.css"];
$sidebarActive = 5;

require_once "../../../db/models/Announcement.php";
require_once "../../../alerts/functions.php";

$announcements = null;
$announcementModel = new Announcement();
try {
    $announcements = $announcementModel->get_all(!($setFilter));
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch announcements: " . $e->getMessage(), "/staff/admin");
    exit;
}


$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ($setFilter == 0) ? 
        ["title" => "Show History", "href" => "/staff/admin/announcements/index.php?filter=1", "type" => "primary"] :
        ["title" => "Show Current", "href" => "/staff/admin/announcements/index.php", "type" => "primary"],
        ["title" => "Create New", "href" => "/staff/admin/announcements/create/index.php", "type" => "secondary"]
    ]
];


require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <?php foreach ($announcements as $announcement) : ?>
            <a href="/staff/admin/announcements/view/index.php?id=<?= $announcement->id ?>" class="announcement-list-item">
                <div style="grid-column:1;">
                    <h3><?= $announcement->title ?></h3>
                    <p>Sent to </strong> <?= $announcement->to_all ?></p>
                    <p><?= substr($announcement->message, 0, 40) ?><?= strlen($announcement->message) > 20 ? '...' : '' ?></p>
                </div>
                <div style="grid-column:2; align-self: end; justify-self: end;">
                    <p><strong>Source:</strong> <?= $announcement->source ?></p>
                    <p><strong>Created on </strong> <?= $announcement->created_at->format('Y-m-d') ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>