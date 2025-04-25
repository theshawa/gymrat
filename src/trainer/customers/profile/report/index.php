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

<style>
    main {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .customer-info {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--color-zinc-800);
    }

    .customer-info h3 {
        margin: 0;
        font-size: 16px;
        text-transform: none;
        letter-spacing: normal;
    }

    .severity-selector {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .severity-selector label {
        display: block;
        font-size: 14px;
        color: var(--color-zinc-400);
        margin-bottom: 10px;
    }

    .severity-options {
        display: flex;
        gap: 10px;
    }

    .severity-option {
        flex: 1;
        text-align: center;
    }

    .severity-option input {
        position: absolute;
        opacity: 0;
    }

    .severity-option span {
        display: block;
        padding: 8px;
        background-color: var(--color-zinc-800);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    /* Using violet shades for severity */
    .severity-option:first-child input:checked+span {
        background-color: var(--color-violet-700);
        color: var(--color-zinc-50);
    }

    .severity-option:nth-child(2) input:checked+span {
        background-color: var(--color-violet-600);
        color: var(--color-zinc-50);
    }

    .severity-option:last-child input:checked+span {
        background-color: var(--color-violet-500);
        color: var(--color-zinc-50);
    }

    .report-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: 5px;
    }

    .complaint-history {
        margin-top: 10px;
    }
</style>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>