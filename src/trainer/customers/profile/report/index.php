<?php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../db/models/Customer.php";
require_once "../../../../db/models/Complaint.php";

// Get customer ID from the URL
$customerId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get trainer ID from session
$trainerId = $_SESSION['auth']['id'] ?? 0;

// Get database connection
$conn = Database::get_conn();

// Get customer data
$customer = null;
if ($customerId > 0) {
    try {
        $customerObj = new Customer();
        $customerObj->id = $customerId;
        if ($customerObj->get_by_id()) {
            $customer = [
                'id' => $customerObj->id,
                'name' => $customerObj->fname . ' ' . $customerObj->lname,
                'email' => $customerObj->email
            ];
        }
    } catch (Exception $e) {
        // Handle error silently
        die("Error fetching customer: " . $e->getMessage());
    }
}

// Get previous complaints/reports made by this trainer about this customer
$previousReports = [];
try {
    $sql = "SELECT * FROM complaints 
            WHERE user_id = :trainer_id 
            AND user_type = 'trainer' 
            AND description LIKE :search_pattern
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $trainerId);
    $stmt->bindValue(':search_pattern', '%"customer_id":' . $customerId . '%');
    $stmt->execute();

    $previousReports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle error silently
    error_log("Error fetching previous reports: " . $e->getMessage());
}

$pageConfig = [
    "title" => "Report Customer",
    "titlebar" => [
        "back_url" => isset($_GET['id']) ? "../?id=" . $_GET['id'] : "../../",
        "title" => "REPORT CUSTOMER"
    ],
    "styles" => ["/trainer/customers/profile/report/report.css", "/trainer/complaint/complaint.css"],
    "navbar_active" => 1
];

require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main>
    <!-- Customer info and report form -->
    <form class="form" action="report_process.php" method="post">
        <input type="hidden" name="customer_id" value="<?= $customerId ?>">

        <?php if ($customer): ?>
            <div class="customer-info">
                <h3>Reporting: <?= htmlspecialchars($customer['name']) ?></h3>
            </div>
        <?php endif; ?>

        <div class="field">
            <select class="input" name="issue_type" required>
                <option value="">Select Issue Type</option>
                <option value="Inappropriate Behavior">Inappropriate Behavior</option>
                <option value="Equipment Misuse">Equipment Misuse</option>
                <option value="Attendance Problem">Attendance Problem</option>
                <option value="Policy Violation">Policy Violation</option>
                <option value="Hygiene Concern">Hygiene Concern</option>
                <option value="Other Issue">Other Issue</option>
            </select>
        </div>

        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>

        <div class="severity-selector">
            <label>Severity Level</label>
            <div class="severity-options">
                <label class="severity-option">
                    <input type="radio" name="severity" value="low">
                    <span>Low</span>
                </label>

                <label class="severity-option">
                    <input type="radio" name="severity" value="medium" checked>
                    <span>Medium</span>
                </label>

                <label class="severity-option">
                    <input type="radio" name="severity" value="high">
                    <span>High</span>
                </label>
            </div>
        </div>

        <button class="btn">Submit Report</button>
        
        <div class="chart-info" style="margin: 20px 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
            <span style="padding-left: 7px">You can only edit or delete your reports <br>within 5 minutes after posting!</span>
        </div>
    </form>

    <!-- Previous Reports/Complaints Section -->
    <div class="complaint-history">
        <h3>Previous Reports on this Client</h3>
        <?php if (empty($previousReports)): ?>
            <p class="paragraph small">No previous reports for this client.</p>
        <?php else: ?>
            <ul class="complaint-list">
                <?php foreach ($previousReports as $report):
                    require_once "../../../../utils.php";
                    $reportData = json_decode($report['description'], true);
                    $reviewed = $report['reviewed_at'] !== null;
                    ?>
                    <li class="complaint-item">
                        <div class="inline">
                            <span class="paragraph small">
                                <?= format_time(new DateTime($report['created_at'])) ?>
                            </span>
                            
                            <?php
                            // Check if report is within 5-minute edit window
                            $now = new DateTime();
                            $created = new DateTime($report['created_at']);
                            $interval = $created->diff($now);
                            $totalMinutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);
                            $isEditable = $totalMinutes < 5;
                            
                            if ($isEditable): 
                            ?>
                            <div class="action-buttons">
                                <a href="./edit/index.php?id=<?= $report['id'] ?>&customer_id=<?= $customerId ?>" class="action-button edit-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        <path d="m15 5 4 4"/>
                                    </svg>
                                </a>
                                <button class="action-button delete-button" onclick="deleteReport(<?= $report['id'] ?>, <?= $customerId ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M3 6h18"/>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <h4 class="type"><?= htmlspecialchars($report['type']) ?></h4>
                        <?php if (isset($reportData) && is_array($reportData)): ?>
                            <div class="report-info">
                                <p class="paragraph"><strong>Severity:</strong>
                                    <?= htmlspecialchars(ucfirst($reportData['severity'] ?? 'medium')) ?></p>
                                <p class="paragraph"><?= htmlspecialchars($reportData['description'] ?? '') ?></p>
                            </div>
                        <?php else: ?>
                            <p class="paragraph"><?= htmlspecialchars($report['description']) ?></p>
                        <?php endif; ?>
                        <div class="review-message <?= $reviewed ? "reviewed" : "pending" ?>">
                            <div class="review-status <?= $reviewed ? "reviewed" : "pending" ?>">
                                <?= $reviewed ? "Reviewed by admin at " . format_time(new DateTime($report['reviewed_at'])) : "To be reviewed" ?>
                            </div>
                            <?php if ($reviewed): ?>
                                <p class="paragraph"><?= htmlspecialchars($report['review_message']) ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<?php require_once "../../../includes/navbar.php" ?>
<script>
function deleteReport(reportId, customerId) {
    if (confirm("Are you sure you want to delete this report? This action cannot be undone.")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = './delete_report_process.php';
        
        const reportIdInput = document.createElement('input');
        reportIdInput.type = 'hidden';
        reportIdInput.name = 'report_id';
        reportIdInput.value = reportId;
        
        const customerIdInput = document.createElement('input');
        customerIdInput.type = 'hidden';
        customerIdInput.name = 'customer_id';
        customerIdInput.value = customerId;
        
        form.appendChild(reportIdInput);
        form.appendChild(customerIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once "../../../includes/footer.php" ?>