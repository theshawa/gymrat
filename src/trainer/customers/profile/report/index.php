<?php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

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

$pageConfig = [
    "title" => "Report Customer",
    "titlebar" => [
        "back_url" => isset($_GET['id']) ? "../?id=" . $_GET['id'] : "../../",
        "title" => "REPORT CUSTOMER"
    ],
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
</style>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>