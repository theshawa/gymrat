<?php
// File path: src/trainer/customers/profile/edit-log/index.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../../db/models/TrainerLogRecord.php";
require_once "../../../../alerts/functions.php";

// Get log ID and customer ID from URL
$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$customerId = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

// If IDs are missing, redirect back
if (!$logId || !$customerId) {
    redirect_with_error_alert("Invalid log record ID", "../add-log/?id=$customerId");
}

// Get the log record
$logRecord = new TrainerLogRecord();
$logRecord->id = $logId;

try {
    $logRecord->get_by_id();
    
    // Check if this log belongs to the current trainer
    $trainer_id = $_SESSION['auth']['id'];
    if ($logRecord->trainer_id != $trainer_id) {
        redirect_with_error_alert("You can only edit your own log records", "../add-log/?id=$customerId");
    }
    
    // Double check the customer ID
    if ($logRecord->customer_id != $customerId) {
        redirect_with_error_alert("Invalid log record for this customer", "../add-log/?id=$customerId");
    }
} catch (Exception $e) {
    redirect_with_error_alert("Log record not found", "../add-log/?id=$customerId");
}

// Get customer details
require_once "../../../../db/Database.php";
$conn = Database::get_conn();
$sql = "SELECT id, fname, lname FROM customers WHERE id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':customer_id', $customerId);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    redirect_with_error_alert("Customer not found", "../");
}

// Handle form submission
$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $performanceType = isset($_POST['performance_type']) ? trim($_POST['performance_type']) : '';
    
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
            // Update the log record
            $logRecord->message = $message;
            $logRecord->performance_type = $performanceType;
            $logRecord->update();
            
            // Redirect with success message
            redirect_with_success_alert("Log record updated successfully", "../add-log/?id=$customerId");
        } catch (Exception $e) {
            $errorMessage = "Error updating log record: " . $e->getMessage();
        }
    } else {
        $errorMessage = implode(", ", $errors);
    }
}

$pageConfig = [
    "title" => "Edit Progress Log",
    "styles" => [
        "../add-log/add-log.css" // Reuse add-log styles
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../add-log/?id=" . $customerId,
        "title" => "EDIT PROGRESS LOG"
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
        <h3>Edit Progress Feedback</h3>

        <form method="POST" class="progress-form">
            <div class="form-group">
                <label for="message">Progress Message</label>
                <input type="text" name="message" id="message" required class="form-control"
                    value="<?= htmlspecialchars($logRecord->message) ?>" maxlength="255">
            </div>

            <div class="form-group performance-type">
                <label>Performance Type</label>
                <div class="radio-buttons">
                    <div class="radio-option">
                        <input type="radio" id="well_done" name="performance_type" value="well_done" required
                            <?= $logRecord->performance_type === 'well_done' ? 'checked' : '' ?>>
                        <label for="well_done">Well Done</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="try_harder" name="performance_type" value="try_harder"
                            <?= $logRecord->performance_type === 'try_harder' ? 'checked' : '' ?>>
                        <label for="try_harder">Try Harder</label>
                    </div>
                </div>
            </div>

            <div class="form">
                <button type="submit" class="btn">Update Your Feedback</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>