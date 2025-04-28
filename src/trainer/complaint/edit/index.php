<?php
// File path: src/trainer/complaint/edit/index.php
require_once "../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../db/models/Complaint.php";
require_once "../../../alerts/functions.php";

// Get complaint ID from URL
$complaintId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If complaint ID is missing, redirect back
if (!$complaintId) {
    redirect_with_error_alert("Invalid complaint ID", "../");
}

// Get the complaint
$complaint = new Complaint();
$complaint->id = $complaintId;

try {
    $complaint->get_by_id($complaintId);
    
    // Check if this complaint belongs to the current user
    $user_id = $_SESSION['auth']['id'];
    if ($complaint->user_id != $user_id || $complaint->user_type != $_SESSION['auth']['role']) {
        redirect_with_error_alert("You can only edit your own complaints", "../");
    }
    
    // Check if the complaint was created less than 5 minutes ago
    $current_time = new DateTime();
    $created_time = $complaint->created_at;
    $edit_time_diff = $current_time->getTimestamp() - $created_time->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("You can only edit complaints within 5 minutes after posting", "../");
    }
    
    // Calculate remaining time
    $remaining_seconds = 300 - $edit_time_diff;
    
} catch (Exception $e) {
    redirect_with_error_alert("Complaint not found", "../");
}

$pageConfig = [
    "title" => "Edit Complaint",
    "styles" => [
        "../complaint.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
        "title" => "EDIT COMPLAINT"
    ]
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main>
    <form class="form" action="edit_complaint_process.php" method="post">
        <input type="hidden" name="id" value="<?= $complaint->id ?>">
        
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Technical Issue" <?= $complaint->type == 'Technical Issue' ? 'selected' : '' ?>>Technical Issue</option>
                <option value="Billing Problem" <?= $complaint->type == 'Billing Problem' ? 'selected' : '' ?>>Billing Problem</option>
                <option value="Facility Feedback" <?= $complaint->type == 'Facility Feedback' ? 'selected' : '' ?>>Facility Feedback</option>
                <option value="Staff Feedback" <?= $complaint->type == 'Staff Feedback' ? 'selected' : '' ?>>Staff Feedback</option>
                <option value="Suggestion" <?= $complaint->type == 'Suggestion' ? 'selected' : '' ?>>Suggestion</option>
                <option value="Other" <?= $complaint->type == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"><?= htmlspecialchars($complaint->description) ?></textarea>
        </div>
        
        <div class="chart-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span id="time-remaining">Time remaining: <span id="countdown"><?= floor($remaining_seconds / 60) ?>:<?= str_pad($remaining_seconds % 60, 2, '0', STR_PAD_LEFT) ?></span></span>
        </div>
        
        <button class="btn">Update Complaint</button>
    </form>
</main>

<script>
// Countdown timer
document.addEventListener('DOMContentLoaded', function() {
    let remainingSeconds = <?= $remaining_seconds ?>;
    const countdownElement = document.getElementById('countdown');
    
    const interval = setInterval(function() {
        remainingSeconds--;
        
        if (remainingSeconds <= 0) {
            clearInterval(interval);
            alert('Edit time expired. You will be redirected back to the complaints page.');
            window.location.href = '../';
            return;
        }
        
        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;
        countdownElement.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }, 1000);
});
</script>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>