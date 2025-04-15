<?php
// File path: src/trainer/customers/profile/add-log/index.php

// Start session and include required files first
session_start();
require_once "../../../../db/Database.php";
require_once "../../../../alerts/functions.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    redirect_with_error_alert("Invalid customer ID", "../../");
    exit;
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
    redirect_with_error_alert("Customer not found", "../../");
    exit;
}

// Handle form submission - do this before including header files
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

            // Use the standard alert system
            redirect_with_success_alert("Progress feedback added successfully", "?id=" . $customerId);
            exit;

        } catch (Exception $e) {
            redirect_with_error_alert("Error saving progress: " . $e->getMessage(), "?id=" . $customerId);
            exit;
        }
    } else {
        redirect_with_error_alert(implode(", ", $errors), "?id=" . $customerId);
        exit;
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
    // Silently handle error - logs array will remain empty
}

// Set up page configuration - only after all the processing is done
$pageConfig = [
    "title" => "Add Progress Log",
    "styles" => [
        "./add-log.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "ADD PROGRESS LOG"
    ],
    "need_auth" => true
];

// Now include the header files which will output content
require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main class="add-log-page">
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

            <div class="form-actions">
                <button type="submit" class="submit-btn">Add Progress Feedback</button>
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
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>