<?php

$pageTitle = "Finance Overview";
$pageStyles = ["./finance.css"];
$sidebarActive = 7;
$menuBarConfig = [
    "title" => $pageTitle
];

// Data for income overview
$incomeOverview = [
    [
        "month" => "November",
        "income" => "Rs. 125,000",
        "plansSold" => 68
    ],
    [
        "month" => "October",
        "income" => "Rs. 94,000",
        "plansSold" => 63
    ],
    [
        "month" => "September",
        "income" => "Rs. 132,000",
        "plansSold" => 69
    ]
];

// Data for membership plans
$membershipPlans = [
    [
        "name" => "Basic Plan",
        "sales" => 120,
        "income" => "Rs. 45,600"
    ],
    [
        "name" => "Standard Plan",
        "sales" => 80,
        "income" => "Rs. 32,000"
    ],
    [
        "name" => "Premium Plan",
        "sales" => 50,
        "income" => "Rs. 25,000"
    ],
    [
        "name" => "VIP Plan",
        "sales" => 30,
        "income" => "Rs. 67,000"
    ]
];

require_once "../pageconfig.php";
require_once "../../../alerts/functions.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div style="display: flex; flex-direction: column">
            <div style="display: flex; flex-direction: row">
                <div class="overview-small-box" style="padding: 20px; display: flex; flex-direction: column; align-items: center">
                    <h1>Income Overview</h1>
                    <div class="tab">
                        <?php foreach ($incomeOverview as $index => $data): ?>
                            <button class="tablinks" onclick="openTab(event, 'Month<?= $index ?>')"><?= $data['month'] ?></button>
                        <?php endforeach; ?>
                    </div>

                    <?php foreach ($incomeOverview as $index => $data): ?>
                        <div id="Month<?= $index ?>" class="tabcontent">
                            <h3><?= $data['month'] ?></h3>
                            <h1 style="font-size: 64px; margin: 50px"><?= $data['income'] ?></h1>
                            <p>Number of membership plans sold: <?= $data['plansSold'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="overview-small-box" style="padding: 20px; display: flex; flex-direction: column; align-items: center">
                    <h1>Income Growth Analysis</h1>
                    
                </div>
            </div>
            <div class="overview-small-box" style="padding: 20px; width: 98%">
                <div class="container">
                    <h2>Membership Plan Sales</h2>
                    <ul class="responsive-table">
                        <li class="table-header">
                            <div class="col col-1">Membership Name</div>
                            <div class="col col-2">Number of Sales</div>
                            <div class="col col-3">Total Income</div>
                        </li>
                        <?php foreach ($membershipPlans as $plan): ?>
                            <li class="table-row">
                                <div class="col col-1" data-label="Membership Name"><?= $plan['name'] ?></div>
                                <div class="col col-2" data-label="Number of Sales"><?= $plan['sales'] ?></div>
                                <div class="col col-3" data-label="Total Income"><?= $plan['income'] ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Open the default tab
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".tablinks").click();
    });
</script>