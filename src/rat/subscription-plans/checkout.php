<?php

require_once "../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

$plan_id = (int) htmlspecialchars($_POST['plan']);

require_once "../../db/models/MembershipPlan.php";

$plan = new MembershipPlan;

$plan->fill([
    'id' => $plan_id,
]);

try {
    $plan->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch plan due to error: " . $e->getMessage(), "./");
}

$pageConfig = [
    "title" => "Payment Confirmation",
    "styles" => [
        "./subscription-plans.css"
    ],
    "need_auth" => false
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

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