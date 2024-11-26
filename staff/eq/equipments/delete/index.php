<?php
$id = $_GET['id'];

require_once "../../pageconfig.php";

// Simulated equipment data (this should be fetched from a database)
$equipment = [
    "id" => 001,
    "name" => "Leg Press Machine",
    "type" => "Legs",
    "last_maintenance" => "2024-10-10",
    "purchase_date" => "2022-05-15",
    "manufacturer" => "FitnessPro Inc.",
    "description" => "A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively",
    "img" => null
];

$pageConfig['styles'][] = "../equipment.css";

require_once "../../../includes/header.php";
// require_once "../../../includes/sidebar.php";
?>

<main>
    <link rel="stylesheet" href="../equipment.css">
    <div class="base-container">
        <form action="delete_workout.php" method="post" class="form">
            <div class="delete-workout-div">
                <h2>Are you sure you want to delete "<?= $equipment["name"] ?>"?</h2>
                <p>This action cannot be undone.</p>
                <input type="hidden" name="id" value="<?= $equipment['id'] ?>">
                <button type="submit">Delete</button>
            </div>
        </form>
    </div>
</main>