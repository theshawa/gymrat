<?php
// File path: src/trainer/announcements/delete/index.php
require_once "../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../db/models/Announcement.php";
require_once "../../../alerts/functions.php";

// Get announcement ID from URL
$announcementId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If announcement ID is missing, redirect back
if (!$announcementId) {
    redirect_with_error_alert("Invalid announcement ID", "../");
}

// Get the announcement
$announcement = new Announcement();
$announcement->id = $announcementId;

try {
    $announcement->get_by_id();
    
    // Check if this announcement belongs to the current trainer
    $trainer_id = $_SESSION['auth']['id'];
    if ($announcement->source != $trainer_id) {
        redirect_with_error_alert("You can only delete your own announcements", "../");
    }
} catch (Exception $e) {
    redirect_with_error_alert("Announcement not found", "../");
}

$pageConfig = [
    "title" => "Delete Announcement",
    "styles" => [
        "../delete-confirmation.css"
    ],
    "navbar_active" => 2,
    "titlebar" => [
        "back_url" => "../",
        "title" => "DELETE ANNOUNCEMENT"
    ]
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="post-announcement-page">
    <div class="confirmation-card">
        <h2>Delete Announcement</h2>
        <p>Are you sure you want to delete this announcement?</p>
        
        <div class="announcement-preview">
            <h3><?= htmlspecialchars($announcement->title) ?></h3>
            <p><?= htmlspecialchars($announcement->message) ?></p>
            <div class="meta">
                <span>Posted: <?= $announcement->created_at->format('M d, Y') ?></span>
                <span>Valid until: <?= $announcement->valid_till->format('M d, Y') ?></span>
            </div>
        </div>
        
        <div class="action-buttons">
            <form action="delete_announcement_process.php" method="POST">
                <input type="hidden" name="id" value="<?= $announcement->id ?>">
                <button type="submit" class="btn danger">Delete</button>
                <a href="../" class="btn secondary">Cancel</a>
            </form>
        </div>
    </div>
</main>


<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>