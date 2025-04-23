<?php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/MealPlan.php";
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

// Get all meal plans
$mealPlans = [];
try {
    $mealPlanModel = new MealPlan();
    $mealPlans = $mealPlanModel->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch meal plans: " . $e->getMessage();
}

// Handle form submission to assign a meal plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_plan'])) {
    $mealPlanId = isset($_POST['meal_plan_id']) ? intval($_POST['meal_plan_id']) : 0;

    if ($mealPlanId > 0) {
        try {
            // Update customer's meal plan ID
            $sql = "UPDATE customers SET meal_plan = :meal_plan_id WHERE id = :customer_id";
            $conn = Database::get_conn();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':meal_plan_id', $mealPlanId);
            $stmt->bindValue(':customer_id', $customerId);
            $stmt->execute();

            // Redirect with success message
            redirect_with_success_alert("Meal plan assigned successfully!", "../?id=" . $customerId);
            exit;
        } catch (Exception $e) {
            redirect_with_error_alert("Failed to assign meal plan: " . $e->getMessage(), "./assign?id=" . $customerId);
            exit;
        }
    } else {
        redirect_with_error_alert("Invalid meal plan selected", "./assign?id=" . $customerId);
        exit;
    }
}

$pageConfig = [
    "title" => "Assign Meal Plan",
    "styles" => [
        "./assign.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "ASSIGN MEAL PLAN"
    ]
];

require_once "../../../../includes/header.php";
require_once "../../../../includes/titlebar.php";
?>

<main class="assignment-page">
    <div class="customer-header">
        <h2>Assign Meal Plan for <?= htmlspecialchars($customer->fname . ' ' . $customer->lname) ?></h2>
    </div>

    <div class="content-container">
        <div class="plans-list">
            <?php if (empty($mealPlans)): ?>
                <div class="empty-state">
                    <p>No meal plans available. You'll need to request a custom plan.</p>
                    <a href="../../meal-plans/request/" class="btn request-btn">Request Custom Plan</a>
                </div>
            <?php else: ?>
                <?php foreach ($mealPlans as $plan): ?>
                    <div class="plan-card">
                        <div class="plan-header">
                            <h3><?= htmlspecialchars($plan->name) ?></h3>
                            <span class="duration"><?= $plan->duration ?> days</span>
                        </div>

                        <p class="plan-description"><?= htmlspecialchars($plan->description) ?></p>

                        <div class="meal-count">
                            <span class="count-label">Meals:</span>
                            <span class="count-value"><?= count($plan->meals) ?></span>
                        </div>

                        <div class="plan-actions">
                            <form method="POST" action="">
                                <input type="hidden" name="meal_plan_id" value="<?= $plan->id ?>">
                                <button type="submit" name="assign_plan" class="btn assign-btn">Assign</button>
                            </form>

                            <a href="./preview?id=<?= $plan->id ?>&customer_id=<?= $customerId ?>"
                                class="btn preview-btn">Preview</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="request-container">
                    <p>Don't see a suitable plan?</p>
                    <form action="../../meal-plan/request/" method="get">
                        <input type="hidden" name="id" value="<?= $customerId ?>">
                        <button type="submit" class="btn request-btn">Request Custom Plan</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>