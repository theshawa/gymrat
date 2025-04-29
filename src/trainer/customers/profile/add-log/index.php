<?php
// File path: src/trainer/customers/profile/add-log/index.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../../db/Database.php";
require_once "../../../../alerts/functions.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    die("Invalid customer ID");
}

// Get database connection
$conn = Database::get_conn();

// Get customer details
$sql = "SELECT id, fname, lname FROM customers WHERE id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':customer_id', $customerId);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    die("Customer not found");
}

// Handle form submission
$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $performanceType = isset($_POST['performance_type']) ? trim($_POST['performance_type']) : '';
    $trainerId = $_SESSION['auth']['id'] ?? 0;

    // Validate form data
    $errors = [];

    if (empty($message)) {
        $errors[] = "Message is required";
    }

    if (empty($performanceType)) {
        $errors[] = "Performance type is required";
    }

    if (count($errors) === 0) {
        try {
            // Check if customer_progress table exists, create it if it doesn't
            $checkTableSql = "SHOW TABLES LIKE 'customer_progress'";
            $checkTableStmt = $conn->prepare($checkTableSql);
            $checkTableStmt->execute();

            if ($checkTableStmt->rowCount() === 0) {
                // Create the table if it doesn't exist
                $createTableSql = "CREATE TABLE `customer_progress` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `customer_id` int NOT NULL,
                    `trainer_id` int NOT NULL,
                    `message` text NOT NULL,
                    `performance_type` enum('well_done','try_harder') NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `customer_progress_customer_id` (`customer_id`),
                    KEY `customer_progress_trainer_id` (`trainer_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

                $createTableStmt = $conn->prepare($createTableSql);
                $createTableStmt->execute();
            }

            // Insert the progress record
            $insertSql = "INSERT INTO customer_progress 
                            (customer_id, trainer_id, message, performance_type) 
                          VALUES 
                            (:customer_id, :trainer_id, :message, :performance_type)";

            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bindValue(':customer_id', $customerId);
            $insertStmt->bindValue(':trainer_id', $trainerId);
            $insertStmt->bindValue(':message', $message);
            $insertStmt->bindValue(':performance_type', $performanceType);
            $insertStmt->execute();
            
            // Notify the customer about the new progress log
            require_once "../../../../notifications/functions.php";
            $trainerName = $_SESSION['auth']['fname'] . ' ' . $_SESSION['auth']['lname'];
            $notificationTitle = "New Progress Feedback";
            $notificationMessage = "Your trainer {$trainerName} has added new progress feedback.";
            notify_rat($customerId, $notificationTitle, $notificationMessage, "trainer");

            // Show success message
            $successMessage = "Progress feedback added successfully";
        } catch (Exception $e) {
            $errorMessage = "Error saving progress: " . $e->getMessage();
        }
    } else {
        $errorMessage = implode(", ", $errors);
    }
}

// Get existing progress records for this customer
$progressLogs = [];
try {
    // Check if the table exists first
    $checkTableSql = "SHOW TABLES LIKE 'customer_progress'";
    $checkTableStmt = $conn->prepare($checkTableSql);
    $checkTableStmt->execute();

    if ($checkTableStmt->rowCount() > 0) {
        $logsSql = "SELECT cp.*, t.fname, t.lname 
                    FROM customer_progress cp
                    LEFT JOIN trainers t ON cp.trainer_id = t.id
                    WHERE cp.customer_id = :customer_id
                    ORDER BY cp.created_at DESC
                    LIMIT 15";
        $logsStmt = $conn->prepare($logsSql);
        $logsStmt->bindValue(':customer_id', $customerId);
        $logsStmt->execute();
        $progressLogs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Error fetching progress logs: " . $e->getMessage());
}

$pageConfig = [
    "title" => "Add Progress Log",
    "styles" => [
        "./add-log.css" // CSS file to be created
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . (isset($_GET['id']) ? $_GET['id'] : ''),
        "title" => "ADD PROGRESS LOG"
    ]
];

require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main class="add-log-page">
    <?php if ($successMessage): ?>
        <div class="notification success">
            <p><?= htmlspecialchars($successMessage) ?></p>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="notification error">
            <p><?= htmlspecialchars($errorMessage) ?></p>
        </div>
    <?php endif; ?>

    <div class="customer-info">
        <h2>Customer: <?= htmlspecialchars($customer['fname'] . ' ' . $customer['lname']) ?></h2>
    </div>

    <div class="progress-form-container">
        <h3>New Progress Feedback</h3>

        <form method="POST" class="progress-form">
            <div class="form-group">
                <label for="message">Progress Message</label>
                <input type="text" name="message" id="message" required class="form-control"
                    placeholder="Example: Great job on your flexibility exercises." maxlength="255">
            </div>

            <div class="form-group performance-type">
                <label>Performance Type</label>
                <div class="radio-buttons">
                    <div class="radio-option">
                        <input type="radio" id="well_done" name="performance_type" value="well_done" required>
                        <label for="well_done">Well Done</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="try_harder" name="performance_type" value="try_harder">
                        <label for="try_harder">Try Harder</label>
                    </div>
                </div>
            </div>

            <div class="form">
                <button style="width=100%" type="submit" class="btn">Add Your Feedback</button>
            </div>
        </form>
    </div>

    <?php if (!empty($progressLogs)): ?>
        <div class="previous-logs">
            <h3>Previous Progress Feedback</h3>

            <div class="logs-list">
                <?php foreach ($progressLogs as $log): ?>
                    <div class="log-item <?= $log['performance_type'] === 'well_done' ? 'well-done' : 'try-harder' ?>">
                        <div class="log-message">
                            <?= htmlspecialchars($log['message']) ?>
                        </div>

                        <div class="log-footer">
                            <span class="log-date"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>

                            <span
                                class="performance-badge <?= $log['performance_type'] === 'well_done' ? 'well-done' : 'try-harder' ?>">
                                <?= $log['performance_type'] === 'well_done' ? 'WELL DONE' : 'TRY HARDER' ?>
                            </span>
                            
                            <?php if ($log['trainer_id'] == $_SESSION['auth']['id']): ?>
                            <div class="log-actions">
                                <a href="../edit-log/?id=<?= $log['id'] ?>&customer_id=<?= $customerId ?>" class="btn-icon edit-log">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        <path d="m15 5 4 4"/>
                                    </svg>
                                </a>
                                <a href="#" class="btn-icon delete-log" onclick="deleteLog(<?= $log['id'] ?>, <?= $customerId ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M3 6h18"/>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once "../../../includes/navbar.php" ?>

<script>
function deleteLog(logId, customerId) {
    if (confirm("Are you sure you want to delete this progress log? This action cannot be undone.")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `../delete-log/delete_process.php`;
        
        const logIdInput = document.createElement('input');
        logIdInput.type = 'hidden';
        logIdInput.name = 'log_id';
        logIdInput.value = logId;
        
        const customerIdInput = document.createElement('input');
        customerIdInput.type = 'hidden';
        customerIdInput.name = 'customer_id';
        customerIdInput.value = customerId;
        
        form.appendChild(logIdInput);
        form.appendChild(customerIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once "../../../includes/footer.php" ?>