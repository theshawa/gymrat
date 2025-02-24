<?php

$pageTitle = "Manage Meals";
$sidebarActive = 4;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create Meal", "href" => "/staff/wnmp/meals/create/index.php", "type" => "secondary"]
    ]
];
$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/meals/view/index.php",
    "cards" => [
        [
            "id" => 101,
            "title" => "Banana",
            "description" => "A high-energy fruit perfect for pre-workout.",
            "image" => null
        ],
        [
            "id" => 102,
            "title" => "Grilled Chicken",
            "description" => "Lean protein source ideal for post-workout recovery.",
            "image" => null
        ],
        [
            "id" => 103,
            "title" => "Oatmeal",
            "description" => "A great source of complex carbohydrates for sustained energy.",
            "image" => null
        ]
    ],
    "isCardInList" => true
];

require_once "../pageconfig.php";

require_once "../../../alerts/functions.php";

$pageConfig['styles'][] = "./meals.css";

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