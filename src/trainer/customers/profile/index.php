<?php
// File: src/trainer/customers/profile/index.php

require_once "../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../db/models/Customer.php";
require_once "../../../db/models/CustomerInitialData.php";
require_once "../../../alerts/functions.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    redirect_with_error_alert("Invalid customer ID", "../");
    exit;
}

// Get customer data from database
$customer = new Customer();
$customer->id = $customerId;
$customerExists = false;

try {
    $customer->get_by_id();
    $customerExists = true;
} catch (Exception $e) {
    die("Error fetching customer: " . $e->getMessage());
}

// Get customer's initial data (including their goal)
$hasInitialData = false;
$goal = "No goal set";

// Try to get initial data but don't fail if it doesn't exist
try {
    $conn = Database::get_conn();
    $sql = "SELECT * FROM customer_initial_data WHERE customer_id = :customer_id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':customer_id', $customerId);
    $stmt->execute();

    $initialData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($initialData) {
        $hasInitialData = true;

        // Convert goal from database code to readable text
        if (!empty($initialData['goal'])) {
            $goalFromDb = $initialData['goal'];

            if ($goalFromDb == "weight_loss") {
                $goal = "Weight loss and fitness improvement";
            } elseif ($goalFromDb == "muscle_gain") {
                $goal = "Muscle gain and strength improvement";
            } elseif ($goalFromDb == "fitness") {
                $goal = "Overall fitness and health improvement";
            } else {
                $goal = $goalFromDb; // Use as-is if not a known code
            }
        } elseif (!empty($initialData['other_goal'])) {
            $goal = $initialData['other_goal'];
        }
    }
} catch (Exception $e) {
    // Just log the error, don't disrupt the flow
    error_log("Error fetching initial data: " . $e->getMessage());
}

$pageConfig = [
    "title" => "Client Status",
    "styles" => [
        "./profile.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
        "title" => "CLIENT STATUS"
    ],
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="profile-view">
    <!-- Customer Profile Section -->
    <div class="profile-section">
        <div class="profile-info">
            <img src="<?= !empty($customer->avatar) ? '/uploads/' . $customer->avatar : '/uploads/default-images/default-avatar.png' ?>"
                alt="Profile" class="profile-avatar">
            <h1 class="profile-name"><?= htmlspecialchars($customer->fname . ' ' . $customer->lname) ?></h1>
            <p class="profile-goal"><?= htmlspecialchars($goal) ?></p>
        </div>
    </div>

    <!-- Action Tiles Section -->
    <div class="action-tiles">
        <div class="action-row">
            <a href="./workout?id=<?= $customerId ?>" class="action-tile purple">
                <span>Current<br>Workout</span>
                <div class="action-arrow">
                    <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                                fill="#FAFAFA" />
                        </svg></span>
                </div>
            </a>
            <a href="./meal-plan?id=<?= $customerId ?>" class="action-tile purple">
                <span>Current<br>Meal Plan</span>
                <div class="action-arrow">
                    <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                                fill="#FAFAFA" />
                        </svg></span>
                </div>
            </a>
        </div>

        <a href="./analytics?id=<?= $customerId ?>" class="action-tile purple">
            <span>View Progress Stats</span>
            <div class="action-arrow">
                <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                            fill="#FAFAFA" />
                    </svg></span>
            </div>
        </a>
        <a href="./add-log?id=<?= $customerId ?>" class="action-tile green">
            <span>Add Log Record</span>
            <div class="action-arrow">
                <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                            fill="#FAFAFA" />
                    </svg></span>
            </div>
        </a>
        <a href="./report?id=<?= $customerId ?>" class="action-tile red">
            <span>Report Client</span>
            <div class="action-arrow">
                <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                            fill="#FAFAFA" />
                    </svg></span>
            </div>
        </a>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>