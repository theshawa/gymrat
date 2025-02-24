<?php

$pageTitle = "Manage Meal Plans";
$sidebarActive = 5;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create Meal Plan", "href" => "/staff/wnmp/meal-plans/create/index.php", "type" => "secondary"]
    ]
];
$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/meal-plans/view/index.php",
    "cards" => [
        [
            "id" => 201,
            "title" => "Weight Loss Plan",
            "description" => "Low-calorie meals with high protein and fiber content.",
            "image" => null
        ],
        [
            "id" => 202,
            "title" => "Muscle Gain Plan",
            "description" => "High-protein meals with balanced carbs and fats.",
            "image" => null
        ],
        [
            "id" => 203,
            "title" => "Maintenance Plan",
            "description" => "Balanced meals to maintain current weight and muscle mass.",
            "image" => null
        ]
    ],
    "isCardInList" => true
];

require_once "../pageconfig.php";

require_once "../../../alerts/functions.php";

$pageConfig['styles'][] = "./meal-plans.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>