<?php

$pageConfig = [
    "title" => "Verify Payment",
    "styles" => [
        "../subscription-plans.css"
    ],
    "need_auth" => true,
    "dont_need_active_subscription" => true,
];

require_once "../../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', '../');
}

require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id'],
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch user due to error: " . $e->getMessage(), "../");
}

$selectedPlanId = $_POST['plan'];
if (!$selectedPlanId) {
    redirect_with_error_alert("No plan selected", "../");
}

$order_id = $user->id . "_" . $selectedPlan['id'] . "_" . time();
$additional_cost = 20;
$amount = $selectedPlan['price'] + $additional_cost;


require_once "../../../payhere.config.php";

$hash = strtoupper(
    md5(
        PAYHERE_CONFIG['merchant_id'] .
            $order_id .
            number_format($amount, 2, '.', '') .
            PAYHERE_CONFIG['currency'] .
            strtoupper(md5(PAYHERE_CONFIG['merchant_secret']))
    )
);

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>
<main>
    <p class="paragraph small">
        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus quaerat modi, aliquid provident, omnis eum facere explicabo ex cupiditate officia repellat, quam repellendus. Dolores, doloribus ducimus dolor repudiandae deleniti autem?
    </p>
    <form action="<?php PAYHERE_CONFIG['url'] ?>" method="post">
        <input type="hidden" name="merchant_id" value="<?= PAYHERE_CONFIG['merchant_id'] ?>">
        <input type="hidden" name="return_url" value="../subscription_success.php">
        <input type="hidden" name="cancel_url" value="./subscription_failed.php">
        <input type="hidden" name="notify_url" value="./subscription_notify.php">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <input type="hidden" name="items" value="<?= $selectedPlan['id'] ?>">
        <input type="hidden" name="currency" value="<?= PAYHERE_CONFIG['currency'] ?>">
        <input type="hidden" name="amount" value="1000">
        <input type="hidden" name="first_name" value="<?= $user->fname ?>">
        <input type="hidden" name="last_name" value="<?= $user->lname ?>">
        <input type="hidden" name="email" value="<?= $user->email ?>">
        <input type="hidden" name="phone" value="<?= $user->phone ?>">
        <input type="hidden" name="address" value="Colombo, Sri Lanka">
        <input type="hidden" name="city" value="Colombo">
        <input type="hidden" name="country" value="Sri Lanka">
        <input type="hidden" name="hash" value="<?= $hash ?>">
        <input type="submit" class="btn">Continue To Payment</input>
    </form>
</main>

<?php require_once "../../includes/footer.php" ?>