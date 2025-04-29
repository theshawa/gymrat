<?php
// File path: src/trainer/customers/profile/report/edit/index.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../../../db/models/Complaint.php";
require_once "../../../../../alerts/functions.php";

// Get report ID and customer ID from URL
$reportId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$customerId = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

// If report ID or customer ID is missing, redirect back
if (!$reportId || !$customerId) {
    redirect_with_error_alert("Invalid report ID or customer ID", "../index.php?id=$customerId");
}

// Get the report
$complaint = new Complaint();
$complaint->id = $reportId;

try {
    $complaint->get_by_id($reportId);
    if (!$complaint->id) {
        redirect_with_error_alert("Report not found", "../index.php?id=$customerId");
    }
    
    // Check if this report belongs to the current trainer
    $trainerId = $_SESSION['auth']['id'];
    if ($complaint->user_id != $trainerId || $complaint->user_type != 'trainer') {
        redirect_with_error_alert("You can only edit your own reports", "../index.php?id=$customerId");
    }
    
    // Parse the JSON data in the description field
    $reportData = json_decode($complaint->description, true);
    if (!$reportData || !isset($reportData['type']) || $reportData['type'] !== 'CUSTOMER REPORT') {
        redirect_with_error_alert("Invalid report format", "../index.php?id=$customerId");
    }
    
    // Check if the report was created less than 5 minutes ago
    $current_time = new DateTime();
    $edit_time_diff = $current_time->getTimestamp() - $complaint->created_at->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("You can only edit reports within 5 minutes after posting", "../index.php?id=$customerId");
    }
    
} catch (Exception $e) {
    redirect_with_error_alert("Error loading report: " . $e->getMessage(), "../index.php?id=$customerId");
}

$pageConfig = [
    "title" => "Edit Report",
    "styles" => [
        "/trainer/customers/profile/report/report.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../index.php?id=$customerId",
        "title" => "EDIT REPORT"
    ]
];

require_once "../../../../includes/header.php";
require_once "../../../../includes/titlebar.php";
?>

<main>
    <!-- Edit report form -->
    <form class="form" action="edit_report_process.php" method="post">
        <input type="hidden" name="report_id" value="<?= $reportId ?>">
        <input type="hidden" name="customer_id" value="<?= $customerId ?>">

        <?php
        // Get customer data
        $conn = Database::get_conn();
        $sql = "SELECT CONCAT(fname, ' ', lname) AS name FROM customers WHERE id = :customer_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="customer-info">
            <h3>Reporting: <?= htmlspecialchars($customer['name'] ?? 'Unknown Customer') ?></h3>
        </div>

        <div class="field">
            <select class="input" name="issue_type" required>
                <option value="">Select Issue Type</option>
                <option value="Inappropriate Behavior" <?= $complaint->type === 'Inappropriate Behavior' ? 'selected' : '' ?>>Inappropriate Behavior</option>
                <option value="Equipment Misuse" <?= $complaint->type === 'Equipment Misuse' ? 'selected' : '' ?>>Equipment Misuse</option>
                <option value="Attendance Problem" <?= $complaint->type === 'Attendance Problem' ? 'selected' : '' ?>>Attendance Problem</option>
                <option value="Policy Violation" <?= $complaint->type === 'Policy Violation' ? 'selected' : '' ?>>Policy Violation</option>
                <option value="Hygiene Concern" <?= $complaint->type === 'Hygiene Concern' ? 'selected' : '' ?>>Hygiene Concern</option>
                <option value="Other Issue" <?= $complaint->type === 'Other Issue' ? 'selected' : '' ?>>Other Issue</option>
            </select>
        </div>

        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"><?= htmlspecialchars($reportData['description'] ?? '') ?></textarea>
        </div>

        <div class="severity-selector">
            <label>Severity Level</label>
            <div class="severity-options">
                <label class="severity-option">
                    <input type="radio" name="severity" value="low" <?= ($reportData['severity'] ?? '') === 'low' ? 'checked' : '' ?>>
                    <span>Low</span>
                </label>

                <label class="severity-option">
                    <input type="radio" name="severity" value="medium" <?= ($reportData['severity'] ?? '') === 'medium' ? 'checked' : '' ?>>
                    <span>Medium</span>
                </label>

                <label class="severity-option">
                    <input type="radio" name="severity" value="high" <?= ($reportData['severity'] ?? '') === 'high' ? 'checked' : '' ?>>
                    <span>High</span>
                </label>
            </div>
        </div>
        
        <div class="edit-time-info">
            <p>Time remaining: <span id="timeRemaining">calculating...</span></p>
        </div>

        <button type="submit" class="btn">Update Report</button>
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
            // Optional: disable form submission
            document.querySelector('button[type="submit"]').disabled = true;
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

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>