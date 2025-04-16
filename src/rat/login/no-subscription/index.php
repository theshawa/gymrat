<?php
require_once "../../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

$pageConfig = [
    "title" => "No Active Subscription",
    "styles" => ["./no-subscription.css"]
];

include_once "../../includes/header.php";

?>

<main class="auth">
    <div class="content">
        <img src="./animation.gif" width="120" height="120" alt="Sad animation" class="animation">
        <h1 class="no-subscription-message">Oops! No active subscription!</h1>

        <a class="btn" href="/rat/subscription-plans">Subscribe to a plan</a>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>