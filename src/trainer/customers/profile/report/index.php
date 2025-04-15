<?php
$pageConfig = [
    "title" => "Report Customer",
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
    $sql = "SELECT * FROM complaints 
            WHERE user_id = :trainer_id 
            AND is_created_by_trainer = 1 
            AND type LIKE '%[Customer:%'
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $trainerId);
    $stmt->execute();

    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process reports to extract severity
    foreach ($reports as &$report) {
        // Extract severity if stored in format "SEVERITY: Description"
        if (preg_match('/^\[([a-zA-Z]+)\]:\s*(.*)$/s', $report['description'], $matches)) {
            $report['severity'] = strtolower($matches[1]);
            $report['clean_description'] = $matches[2];
        } else {
            $report['severity'] = 'medium'; // Default
            $report['clean_description'] = $report['description'];
        }

        // Extract customer ID if stored in format "[Customer:ID]"
        if (preg_match('/\[Customer:(\d+)\]/', $report['type'], $matches)) {
            $report['customer_id'] = $matches[1];
        }
    }

} catch (Exception $e) {
    // Handle error silently
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
                // Check if this report is for the current customer
                if (isset($report['customer_id']) && $report['customer_id'] != $customerId) {
                    continue;
                }

                // Determine status class and text
                $statusClass = 'status-pending';
                $statusText = 'Pending';

                if (isset($report['status'])) {
                    if ($report['status'] == 'in_progress') {
                        $statusClass = 'status-in-progress';
                        $statusText = 'In progress';
                    } else if ($report['status'] == 'resolved' || $report['status'] == 'reviewed') {
                        $statusClass = 'status-resolved';
                        $statusText = 'Resolved';
                    } else if ($report['status'] == 'dismissed') {
                        $statusClass = 'status-dismissed';
                        $statusText = 'Dismissed';
                    }
                }

                // Admin has replied if admin_reply is set
                $hasAdminReply = isset($report['admin_reply']) && !empty($report['admin_reply']);
                if ($hasAdminReply) {
                    $statusClass = 'status-resolved';
                    $statusText = 'Reviewed';
                }
                ?>

                <div class="report-item">
                    <div class="report-header">
                        <h3><?= htmlspecialchars(preg_replace('/\[Customer:\d+\]\s*/', '', $report['type'])) ?></h3>
                        <span class="report-status <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </div>

                    <p class="report-description">
                        <?= htmlspecialchars($report['clean_description'] ?? $report['description']) ?></p>

                    <div class="report-footer">
                        <span class="report-date">
                            <?= date('M d, Y', strtotime($report['created_at'])) ?>
                        </span>

                        <span class="severity-badge severity-<?= $report['severity'] ?>">
                            <?= ucfirst($report['severity']) ?>
                        </span>
                    </div>

                    <?php if ($hasAdminReply): ?>
                        <div class="admin-reply">
                            <div class="reply-header">
                                <span class="admin-label">Admin Response</span>
                                <?php if (!empty($report['replied_at'])): ?>
                                    <span class="reply-date">
                                        <?= date('M d, Y', strtotime($report['replied_at'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p><?= htmlspecialchars($report['admin_reply']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<style>
    main {
        display: flex;
        flex-direction: column;
        gap: 20px;
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

    .previous-reports-title {
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .reports-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .report-item {
        background-color: var(--color-zinc-900);
        border-radius: 10px;
        padding: 15px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .report-status {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 12px;
    }

    .status-pending {
        background-color: var(--color-zinc-800);
        color: var(--color-zinc-400);
    }

    .status-in-progress {
        background-color: var(--color-zinc-700);
        color: var(--color-zinc-200);
    }

    .status-resolved {
        background-color: var(--color-green);
        color: var(--color-zinc-50);
    }

    .status-dismissed {
        background-color: var(--color-red);
        color: var(--color-zinc-50);
    }

    .report-description {
        font-size: 14px;
        color: var(--color-zinc-400);
        margin: 10px 0;
        line-height: 1.5;
    }

    .report-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--color-zinc-800);
    }

    .report-date {
        font-size: 12px;
        color: var(--color-zinc-500);
    }

    .severity-badge {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 12px;
    }

    /* Using violet shades for severity badges */
    .severity-low {
        background-color: var(--color-violet-700);
        color: var(--color-zinc-50);
    }

    .severity-medium {
        background-color: var(--color-violet-600);
        color: var(--color-zinc-50);
    }

    .severity-high {
        background-color: var(--color-violet-500);
        color: var(--color-zinc-50);
    }

    .admin-reply {
        background-color: var(--color-zinc-800);
        padding: 12px;
        margin-top: 10px;
        border-radius: 8px;
    }

    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .admin-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-green-light);
    }

    .reply-date {
        font-size: 11px;
        color: var(--color-zinc-500);
    }

    .admin-reply p {
        margin: 0;
        color: var(--color-zinc-300);
        font-size: 13px;
        line-height: 1.5;
    }
</style>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>