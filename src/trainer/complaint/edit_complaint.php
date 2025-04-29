<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../db/models/Complaint.php";
require_once "../../alerts/functions.php";

$complaintId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$complaintId) {
    redirect_with_error_alert("Invalid complaint ID", "./");
    exit;
}

// Get the complaint details
$complaint = new Complaint();
$complaint->id = $complaintId;

try {
    if (!$complaint->get_by_id()) {
        redirect_with_error_alert("Complaint not found", "./");
        exit;
    }
    
    // Verify this complaint belongs to the current trainer
    if ($complaint->user_id != $_SESSION['auth']['id'] || $complaint->user_type != 'trainer') {
        redirect_with_error_alert("You don't have permission to edit this complaint", "./");
        exit;
    }
    
    // Check if the complaint is within the 5-minute edit window
    $now = new DateTime();
    $created = $complaint->created_at;
    $interval = $created->diff($now);
    $totalMinutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);
    
    if ($totalMinutes >= 5) {
        redirect_with_error_alert("This complaint can no longer be edited (5-minute window has passed)", "./");
        exit;
    }
    
} catch (Exception $e) {
    redirect_with_error_alert("Error loading complaint: " . $e->getMessage(), "./");
    exit;
}

// Handle form submission for updating the complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    if (empty($type) || empty($description)) {
        $error = "Type and description are required";
    } else {
        try {
            $complaint->type = $type;
            $complaint->description = $description;
            $complaint->update();
            
            redirect_with_success_alert("Complaint updated successfully", "./");
            exit;
        } catch (Exception $e) {
            $error = "Error updating complaint: " . $e->getMessage();
        }
    }
}

$pageConfig = [
    "title" => "Edit Complaint",
    "titlebar" => [
        "back_url" => "./",
        "title" => "EDIT COMPLAINT"
    ],
    "styles" => ["./complaint.css"],
    "navbar_active" => 1
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form class="form" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Technical Issue" <?= $complaint->type === 'Technical Issue' ? 'selected' : '' ?>>Technical Issue</option>
                <option value="Billing Problem" <?= $complaint->type === 'Billing Problem' ? 'selected' : '' ?>>Billing Problem</option>
                <option value="Facility Feedback" <?= $complaint->type === 'Facility Feedback' ? 'selected' : '' ?>>Facility Feedback</option>
                <option value="Staff Feedback" <?= $complaint->type === 'Staff Feedback' ? 'selected' : '' ?>>Staff Feedback</option>
                <option value="Suggestion" <?= $complaint->type === 'Suggestion' ? 'selected' : '' ?>>Suggestion</option>
                <option value="Other" <?= $complaint->type === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"><?= htmlspecialchars($complaint->description) ?></textarea>
        </div>
        
        <div class="edit-time-info">
            <p>Time remaining: <span id="timeRemaining">calculating...</span></p>
        </div>
        
        <button type="submit" class="btn">Update Complaint</button>
    </form>
</main>

<script>
    // Calculate and display time remaining
    function updateTimeRemaining() {
        const createdAt = new Date('<?= $complaint->created_at->format('c') ?>');
        const fiveMinutesLater = new Date(createdAt.getTime() + (5 * 60 * 1000));
        const now = new Date();
        
        // Calculate time difference in milliseconds
        const timeDiff = fiveMinutesLater - now;
        
        if (timeDiff <= 0) {
            document.getElementById('timeRemaining').textContent = 'Time expired';
            // Optional: disable form submission or redirect
            return;
        }
        
        // Convert to minutes and seconds
        const minutes = Math.floor(timeDiff / 60000);
        const seconds = Math.floor((timeDiff % 60000) / 1000);
        
        // Display remaining time
        document.getElementById('timeRemaining').textContent = 
            `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }
    
    // Update time remaining every second
    updateTimeRemaining();
    setInterval(updateTimeRemaining, 1000);
</script>

<style>
    .edit-time-info {
        margin-top: 15px;
        margin-bottom: 15px;
        text-align: center;
        font-size: 14px;
        background-color: rgba(59, 130, 246, 0.1);
        padding: 10px;
        border-radius: 6px;
    }
    
    .edit-time-info p {
        margin: 0;
        font-weight: 500;
    }
    
    #timeRemaining {
        font-weight: 700;
        color: #3b82f6;
    }
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>