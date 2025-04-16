<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

require_once "../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

$pageConfig = [
    "title" => "Payment Confirmation",
    "styles" => [
        "./subscription-plans.css"
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

$plan_id = (int) htmlspecialchars($_POST['plan']);

require_once "../../db/models/MembershipPlan.php";

$plan = new MembershipPlan;

$plan->fill([
    'id' => $plan_id,
]);

try {
    $plan->get_by_id();
} catch (PDOException $e) {
    die("Failed to get plan due to error: " . $e->getMessage());
}

?>

<main>
    <h2>
        Are You Sure You Want To Subscribe To This Plan?
    </h2>
    <br>
    <p class="paragraph">
        Selected Plan is <?= $plan->name ?>. You will be charged <?= number_format($plan->price) ?> LKR for this plan.
    </p>
    <form action="checkout_process.php" method="post">
        <input type="hidden" name="plan" value="<?= $plan->id ?>">
        <button class="btn">Continue with the Payment</button>
    </form>
</main>

<?php require_once "../includes/footer.php" ?>