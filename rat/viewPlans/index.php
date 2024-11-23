<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $pageConfig = [
        "title" => "Home",
        "styles" => [
            "./viewPlans.css"
        ],
        "navbar_active" => 3,
        "titlebar" => [
            "title" => "View Plans",
        ]
    ];

    include_once "../includes/header.php";
    ?>
</head>

<body>

    <!-- Title Bar -->
    <div class="title-bar">
        <!-- Back arrow button to go back to the previous page -->
        <button class="back-arrow" onclick="history.back()">&#8592;</button>        
        <h1>SUBSCRIPTION PLANS</h1>
    </div>

    <!-- Description -->
    <p class="description">
        A gym administrator, often referred to as a gym manager, is responsible for overseeing the day-to-day operations and management of a fitness facility.
    </p>

    <!-- Subscription Plans List -->
    <form action="planConfirmation.php" method="post" class="plans-container">
        <?php
        $plans = [
            ["title" => "Monthly", "price" => "3200 LKR", "value" => "monthly"],
            ["title" => "3 Months", "price" => "9000 LKR", "value" => "3months"],
            ["title" => "6 Months", "price" => "16000 LKR", "value" => "6months"],
            ["title" => "12 Months", "price" => "30000 LKR", "value" => "12months"],
            ["title" => "24 Months", "price" => "55000 LKR", "value" => "24months"]
        ];

        foreach ($plans as $index => $plan) {
            $checked = $index === 0 ? "checked" : ""; // Default to first option being checked
            echo "
            <label class='plan-item'>
                <input type='radio' name='selected_plan' value='{$plan['title']}' $checked>
                <div class='circle'>
                    <div class='inner-dot'></div>
                </div>
                <span class='plan-title'>{$plan['title']}</span>
                <span class='plan-price'>{$plan['price']}</span>
            </label>
            ";
        }
        ?>
        <button type="submit" class="buy-button">BUY SELECTED PLAN</button>
    </form>

    <?php include_once "../includes/footer.php"; ?>

</body>
</html>