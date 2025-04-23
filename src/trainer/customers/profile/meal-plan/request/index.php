<?php
// File: src/trainer/meal-plans/request/index.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../db/models/Customer.php";
require_once "../../../../../alerts/functions.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    redirect_with_error_alert("Invalid customer ID", "../../");
    exit;
}

// Get customer data
$customer = new Customer();
$customer->id = $customerId;
try {
    $customer->get_by_id();
} catch (Exception $e) {
    die("Error fetching customer: " . $e->getMessage());
}

$pageConfig = [
    "title" => "Request Meal Plan",
    "navbar_active" => 1,
    "styles" => [
        "./mealplanreq.css"
    ],
    "titlebar" => [
        "back_url" => "../",
        "title" => "REQUEST MEAL PLAN"
    ]
];

require_once "../../../../includes/header.php";
require_once "../../../../includes/titlebar.php";
?>

<main>
    <form class="form" action="request_process.php" method="post">
        <input type="hidden" name="customer_id" value="<?= $customerId ?>">
        <div class="form-field">
            <label for="description">Meal Plan Details</label>
            <textarea id="description" name="description" class="input" rows="6" required
                placeholder="Describe the meal plan you need (client goals, dietary preferences, restrictions, etc.)"></textarea>
        </div>

        <div class="form-field">
            <label for="client_goal">Primary Goal</label>
            <select id="client_goal" name="client_goal" class="input" required>
                <option value="">Select Goal</option>
                <option value="weight_loss">Weight Loss</option>
                <option value="muscle_gain">Muscle Gain</option>
                <option value="maintenance">Maintenance</option>
                <option value="performance">Athletic Performance</option>
                <option value="health">General Health</option>
            </select>
        </div>

        <button type="submit" class="btn">Submit Request</button>
    </form>
</main>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>