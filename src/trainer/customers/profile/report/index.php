<?php
$pageConfig = [
    "title" => "Report Customer",
    "styles" => [
        "./report.css"
    ],
    "titlebar" => [
        "back_url" => isset($_GET['id']) ? "../?id=" . $_GET['id'] : "../../",
        "title" => "REPORT CUSTOMER"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
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
    }
}

// Fetch complaints made by this trainer for this customer
$reports = [];
try {
    // UPDATED QUERY: Search in both type (for legacy data) and description (for new format)
    $sql = "SELECT * FROM complaints 
            WHERE user_id = :trainer_id 
            AND user_type = 'trainer' 
            AND (
                type LIKE :customer_pattern 
                OR description LIKE :customer_id_desc
            )
            ORDER BY created_at DESC";

    $customerPattern = "Customer #" . $customerId . "%";
    $customerIdDesc = "%[Customer #" . $customerId . "]%";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $trainerId);
    $stmt->bindValue(':customer_pattern', $customerPattern);
    $stmt->bindValue(':customer_id_desc', $customerIdDesc);
    $stmt->execute();

    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process reports to extract severity
    foreach ($reports as &$report) {
        // Extract severity if stored in format "[Severity: LEVEL] Description"
        if (preg_match('/\[Severity:\s*([a-zA-Z]+)\](.*)/s', $report['description'], $matches)) {
            $report['severity'] = strtolower(trim($matches[1]));
            $report['clean_description'] = trim($matches[2]);
        } else {
            $report['severity'] = 'medium'; // Default
            $report['clean_description'] = $report['description'];
        }

        // Extract customer ID from description if it exists in the new format
        if (preg_match('/\[Customer #(\d+)\]/', $report['description'], $customerMatches)) {
            // This is a report in the new format
            // We can use this if needed
        }

        // For display purposes in the UI, strip the customer ID from type if it exists
        // This is for legacy data where customer ID is in the type field
        $report['display_type'] = preg_replace('/^Customer #\d+ - /', '', $report['type']);
    }

} catch (Exception $e) {
    // Handle error silently - but could log it for debugging
    // error_log("Error fetching reports: " . $e->getMessage());
}
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
    </form>

    <!-- Previous reports section -->
    <?php if (!empty($reports)): ?>
        <h2 class="previous-reports-title">PREVIOUS REPORTS</h2>

        <div class="reports-list">
            <?php foreach ($reports as $report): ?>
                <?php
                // Determine status class and text
                $statusClass = 'status-pending';
                $statusText = 'Pending';

                if (isset($report['reviewed_at']) && $report['reviewed_at']) {
                    $statusClass = 'status-resolved';
                    $statusText = 'Reviewed';
                }
                ?>

                <div class="report-item">
                    <div class="report-header">
                        <h3><?= htmlspecialchars(isset($report['display_type']) ? $report['display_type'] : $report['type']) ?>
                        </h3>
                        <span class="report-status <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </div>

                    <p class="report-description">
                        <?= htmlspecialchars($report['clean_description']) ?>
                    </p>

                    <div class="report-footer">
                        <span class="report-date">
                            <?= date('M d, Y', strtotime($report['created_at'])) ?>
                        </span>

                        <span class="severity-badge severity-<?= $report['severity'] ?>">
                            <?= ucfirst($report['severity']) ?>
                        </span>
                    </div>

                    <?php if (isset($report['review_message']) && !empty($report['review_message'])): ?>
                        <div class="admin-reply">
                            <div class="reply-header">
                                <span class="admin-label">Admin Response</span>
                                <?php if (isset($report['reviewed_at']) && !empty($report['reviewed_at'])): ?>
                                    <span class="reply-date">
                                        <?= date('M d, Y', strtotime($report['reviewed_at'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p><?= htmlspecialchars($report['review_message']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>