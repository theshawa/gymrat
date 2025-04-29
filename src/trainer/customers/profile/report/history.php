<?php
// File: src/trainer/customers/profile/report/history.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    header("Location: ../../");
    exit;
}

// Get database connection
require_once "../../../../db/Database.php";
$conn = Database::get_conn();

// Get customer details
$sql = "SELECT id, fname, lname FROM customers WHERE id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':customer_id', $customerId);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    die("Customer not found.");
}

// Get trainer ID from session
$trainerId = $_SESSION['auth']['id'] ?? 0;

// Get all reports filed by this trainer for this customer
$reports = [];
try {
    $sql = "SELECT * FROM complaints 
            WHERE user_id = :trainer_id 
            AND user_type = 'trainer' 
            AND description LIKE '%\"customer_id\":" . $customerId . "%'
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $trainerId);
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error silently
    error_log("Error fetching reports: " . $e->getMessage());
}

// Process reports to extract structured data
foreach ($reports as $key => $report) {
    // Try to decode JSON data from description field
    $reportData = json_decode($report['description'], true);
    if ($reportData && isset($reportData['type']) && $reportData['type'] === 'CUSTOMER REPORT') {
        $reports[$key]['reportType'] = $reportData['type'] ?? 'Unknown';
        $reports[$key]['severity'] = $reportData['severity'] ?? 'medium';
        $reports[$key]['reportDescription'] = $reportData['description'] ?? '';
    } else {
        // If not in expected format, use the raw description
        $reports[$key]['reportDescription'] = $report['description'];
        $reports[$key]['severity'] = 'medium';
    }
}

$pageConfig = [
    "title" => "Report History",
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "./?id=" . $customerId,
        "title" => "REPORT HISTORY"
    ]
];

require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main>
    <div class="customer-header">
        <h2><?= htmlspecialchars($customer['fname'] . ' ' . $customer['lname']) ?> - Report History</h2>
    </div>

    <?php if (empty($reports)): ?>
        <div class="no-reports">
            <p>No reports submitted yet.</p>
        </div>
    <?php else: ?>
        <div class="reports-list">
            <?php foreach ($reports as $report): ?>
                <div class="report-item severity-<?= htmlspecialchars($report['severity']) ?>">
                    <div class="report-header">
                        <span class="report-type"><?= htmlspecialchars($report['type']) ?></span>
                        <span class="report-status <?= $report['reviewed_at'] ? 'resolved' : 'pending' ?>">
                            <?= $report['reviewed_at'] ? 'Reviewed' : 'Pending Review' ?>
                        </span>
                    </div>
                    <div class="report-body">
                        <p class="report-description">
                            <?= htmlspecialchars($report['reportDescription'] ?? $report['description']) ?></p>
                    </div>
                    <div class="report-footer">
                        <span class="report-date"><?= date('M d, Y - h:i A', strtotime($report['created_at'])) ?></span>
                        <span class="report-severity <?= $report['severity'] ?>"><?= ucfirst($report['severity']) ?></span>
                        
                        <?php
                        // Check if report is within 5-minute edit window
                        $now = new DateTime();
                        $created = new DateTime($report['created_at']);
                        $interval = $created->diff($now);
                        $totalMinutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);
                        $isEditable = $totalMinutes < 5;
                        
                        if ($isEditable): 
                        ?>
                        <div class="report-actions">
                            <a href="#" class="delete-report-btn" onclick="confirmDelete(<?= $report['id'] ?>, <?= $customerId ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($report['reviewed_at']): ?>
                        <div class="report-review">
                            <div class="review-header">
                                <span class="review-title">Staff Response:</span>
                                <span class="review-date"><?= date('M d, Y', strtotime($report['reviewed_at'])) ?></span>
                            </div>
                            <p class="review-message"><?= htmlspecialchars($report['review_message']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="action-buttons">
        <a href="./?id=<?= $customerId ?>" class="btn">Submit New Report</a>
    </div>
    
    <div class="edit-notice">
        <p>You have 5 minutes to make changes to published reports or complaints.</p>
    </div>
</main>

<style>
    main {
        padding: 15px;
    }

    .customer-header {
        margin-bottom: 20px;
    }

    .customer-header h2 {
        font-size: 18px;
        margin: 0;
    }

    .no-reports {
        background-color: var(--color-zinc-900);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .no-reports p {
        color: var(--color-zinc-400);
        margin: 0;
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
        border-left: 4px solid transparent;
    }

    .report-item.severity-low {
        border-left-color: #FBBF24;
        /* Yellow */
    }

    .report-item.severity-medium {
        border-left-color: #F97316;
        /* Orange */
    }

    .report-item.severity-high {
        border-left-color: #EF4444;
        /* Red */
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .report-type {
        font-weight: 600;
        color: var(--color-zinc-100);
        font-size: 14px;
    }

    .report-status {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
    }

    .report-status.pending {
        background-color: rgba(251, 191, 36, 0.2);
        color: #FBBF24;
    }

    .report-status.resolved {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10B981;
    }

    .report-body {
        margin-bottom: 10px;
    }

    .report-description {
        margin: 0;
        color: var(--color-zinc-300);
        font-size: 14px;
        line-height: 1.4;
    }

    .report-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--color-zinc-800);
        padding-top: 8px;
        margin-top: 8px;
    }

    .report-date {
        color: var(--color-zinc-500);
        font-size: 12px;
    }

    .report-severity {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 30px;
        font-weight: 500;
    }

    .report-severity.low {
        background-color: rgba(251, 191, 36, 0.2);
        color: #FBBF24;
    }

    .report-severity.medium {
        background-color: rgba(249, 115, 22, 0.2);
        color: #F97316;
    }

    .report-severity.high {
        background-color: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .report-review {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed var(--color-zinc-800);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .review-title {
        font-weight: 500;
        color: var(--color-zinc-200);
        font-size: 13px;
    }

    .review-date {
        color: var(--color-zinc-500);
        font-size: 12px;
    }

    .review-message {
        margin: 0;
        color: var(--color-zinc-300);
        font-size: 13px;
        line-height: 1.5;
        font-style: italic;
    }

    .action-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .btn {
        background-color: var(--color-violet-600);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }
    
    .report-actions {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    
    .delete-report-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #EF4444;
        background-color: rgba(239, 68, 68, 0.1);
        border-radius: 4px;
        width: 28px;
        height: 28px;
        transition: all 0.2s ease;
    }
    
    .delete-report-btn:hover {
        background-color: rgba(239, 68, 68, 0.2);
    }
    
    .edit-notice {
        margin-top: 15px;
        text-align: center;
        padding: 10px;
        background-color: rgba(249, 115, 22, 0.1);
        border-radius: 8px;
    }
    
    .edit-notice p {
        margin: 0;
        color: #F97316;
        font-size: 12px;
        font-style: italic;
    }
</style>

<?php require_once "../../../includes/navbar.php" ?>
<script>
function confirmDelete(reportId, customerId) {
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