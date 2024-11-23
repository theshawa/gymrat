<?php
// Retrieve the selected plan name from the POST data
$selectedPlan = isset($_POST['selected_plan']) ? $_POST['selected_plan'] : "a plan";

// Set a dummy expiration date for demonstration (e.g., one month from today)
$expirationDate = date('d/m/Y', strtotime('+1 month'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Confirmation</title>
    <link rel="stylesheet" href="planConfirmation.css">
</head>
<body>

    <!-- Title Bar -->
    <div class="title-bar">
    <button class="back-arrow" onclick="history.back()">&#8592;</button>        
    <h1>PLAN CONFIRMATION</h1>
    </div>

    <!-- Confirmation Message -->
    <div class="confirmation-content">
        <h2 class="plan-selected-message">You have selected the <?php echo htmlspecialchars($selectedPlan); ?> Plan!</h2>
        <p class="expiration-message">Your plan will expire on <?php echo $expirationDate; ?></p>

        <!-- Proceed to Checkout Button -->
        <button class="checkout-button" onclick="window.location.href='/path/to/checkout.php'">PROCEED TO CHECKOUT</button>

        <!-- Change Plan Link -->
        <p class="change-plan" onclick="history.back()">Change Plan</p>

    </div>

</body>
</html>