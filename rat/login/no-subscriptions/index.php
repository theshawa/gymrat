<?php
$pageConfig = [
    "title" => "No Active Subscription",
    "styles" => ["./noSub.css"],
];

include_once "../../includes/header.php";

?>

<main>
    <div class="content">
        <img src="../../assets/logo-gray.svg" alt="GYMRAT Logo" class="logo">
        <h1 class="no-subscription-message">No active subscription!</h1>
        
        <button class="view-plans-btn" onclick="window.location.href='../../viewPlans'">View Plans</button>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>