<?php
$pageConfig = [
    "title" => "No Active Subscription",
    "styles" => ["./no-subscription.css"],
    "need_auth" => true,
    "dont_need_active_subscription" => true
];

include_once "../../includes/header.php";

?>

<main class="auth">
    <img src="./animation.gif" width="120" height="120" alt="Sad animation" class="animation">
    <h1 class="no-subscription-message">Oops! No active subscription!</h1>

    <a class="btn" href="/rat/subscription-plans">Subscribe to a plan</a>
</main>

<?php include_once "../../includes/footer.php" ?>