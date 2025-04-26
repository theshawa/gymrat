<?php
// File path: src/trainer/announcements/edit/index.php
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
        redirect_with_error_alert("You can only edit your own announcements", "../");
    }
    
    // Check if the announcement was created less than 5 minutes ago
    $current_time = new DateTime();
    $edit_time_diff = $current_time->getTimestamp() - $announcement->created_at->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("You can only edit announcements within 5 minutes after posting", "../");
    }
    
} catch (Exception $e) {
    redirect_with_error_alert("Announcement not found", "../");
}

$pageConfig = [
    "title" => "Edit Announcement",
    "styles" => [
        "./edit-announcement.css"
    ],
    "navbar_active" => 2,
    "titlebar" => [
        "back_url" => "../",
        "title" => "EDIT ANNOUNCEMENT"
    ]
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="edit-announcement-page">

    <form action="edit_announcement_process.php" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $announcement->id ?>">
        
        <div class="field">
            <label for="title">Announcement Title</label>
            <input type="text" name="title" id="title" required class="input" 
                   value="<?= htmlspecialchars($announcement->title) ?>" 
                   placeholder="Enter announcement title">
        </div>
        
        <div class="field">
            <label for="message">Announcement Message</label>
            <textarea name="message" id="message" required class="input" 
                      placeholder="Enter announcement message"><?= htmlspecialchars($announcement->message) ?></textarea>
        </div>
        
        <div class="field">
            <label for="valid_till">Valid Until</label>
            <input type="date" name="valid_till" id="valid_till" required 
                   value="<?= $announcement->valid_till->format('Y-m-d') ?>" 
                   min="<?= date('Y-m-d') ?>" 
                   class="input"> 
        </div>
        
        <div>
            <button type="submit" style="width:100%" class="btn">Update Announcement</button>
        </div>
    </form>
</main

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>