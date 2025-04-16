<?php
require_once "../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

$pageConfig = [
    "title" => "Subscription Plans",
    "styles" => [
        "./subscription-plans.css"
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "../../db/models/MembershipPlan.php";

$planModel = new MembershipPlan;

try {
    $plans = $planModel->get_all();
} catch (PDOException $e) {
    die("Failed to fetch plans due to error: " . $e->getMessage());
}
?>

<main>
    <p class="paragraph small">
        The following plans are created by the gym owner. Customers have to pay the amount mentioned for each plan to subscribe to that plan.
    </p>
    <form action="checkout.php" method="post">
        <div class="plans">
            <?php foreach ($plans as $i => $plan): ?>
                <label class="plan">
                    <input type="radio" name="plan" value="<?= $plan->id ?>" required <?= $i === 0 ? "checked" : "" ?>>
                    <div class="tick">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h3 class="title"><?= $plan->name ?></h3>
                    <span class="price"><?= number_format($plan->price) ?> LKR</span>
                </label>
            <?php endforeach; ?>
        </div>
        <button class="btn">Activate Plan</button>
    </form>
</main>

<?php require_once "../includes/footer.php" ?>