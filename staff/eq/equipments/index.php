<?php

$pageTitle = "Manage Equipments";
$sidebarActive = 2;

$menuBarConfig = [
    "title" => "Manage Equipments",
    "useLink" => true,
    "options" => [
        ["title" => "New Equipment", "href" => "/staff/eq/equipments/new/index.php", "type" => "secondary"],
        // ["title" => "Delete Equipment", "href" => "/staff/eq/equipments/delete/index.php?id=$id", "type" => "destructive"]
    ]
];

// All equipment items in a single list
$equipmentList = [
    [
        "id" => 1,
        "name" => "Leg Press Machine",
        "description" => "A versatile machine designed to target quadriceps, hamstrings, and glutes effectively."
    ],
    [
        "id" => 2,
        "name" => "Squat Rack",
        "description" => "A rack for performing squats and other compound exercises."
    ],
    [
        "id" => 3,
        "name" => "Leg Curl Machine",
        "description" => "Designed to isolate and strengthen the hamstrings."
    ],
    [
        "id" => 4,
        "name" => "Calf Raise Machine",
        "description" => "Targets and strengthens the calf muscles."
    ],
    [
        "id" => 5,
        "name" => "Bench Press",
        "description" => "A classic equipment for chest and triceps strength training."
    ],
    [
        "id" => 6,
        "name" => "Chest Fly Machine",
        "description" => "Builds chest muscles and improves posture."
    ],
    [
        "id" => 7,
        "name" => "Lat Pulldown Machine",
        "description" => "A machine for strengthening the back and biceps."
    ],
    [
        "id" => 8,
        "name" => "Dumbbells",
        "description" => "Versatile free weights for full-body strength training."
    ]
];

$infoCardConfig = [
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/eq/equipments/view/index.php",
    "cards" => $equipmentList,
    "isCardInList" => true
];

require_once "../pageconfig.php";
$pageConfig['styles'][] = "./equipment.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
<!--        <h1>Manage Equipments | Equipment Manager</h1>-->
<!--        <div class="equipment-list mt-4">-->
<!--            <ul>-->
<!--                --><?php //foreach ($equipmentList as $equipment): ?>
<!--                    <li class="equipment-item">-->
<!--                        <a href="/staff/eq/equipments/view/index.php?id=--><?php //echo htmlspecialchars($equipment['id']); ?><!--">-->
<!--                            <strong>--><?php //echo htmlspecialchars($equipment['name']); ?><!--</strong>-->
<!--                        </a>-->
<!--                        <p class="description">--><?php //echo htmlspecialchars($equipment['description']); ?><!--</p>-->
<!--                    </li>-->
<!--                    <hr />-->
<!--                --><?php //endforeach; ?>
<!--            </ul>-->
<!--        </div>-->
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>
