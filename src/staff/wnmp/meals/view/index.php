<?php
$id = $_GET['id'] ?? null;

$sidebarActive = 4;

require_once "../../../../db/models/Meal.php";
require_once "../../../../alerts/functions.php";

$meal = new Meal();
try {
    $meal->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal: " . $e->getMessage(), "/staff/wnmp/meals");
}

$menuBarConfig = [
    "title" => $meal->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meals/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Meal", "href" => "/staff/wnmp/meals/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Meal", "href" => "/staff/wnmp/meals/delete/index.php?id=$id", "type" => "destructive"]
    ]
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="view-meal-container">
            <div>
                <div class="view-meal-details">
                    <h2>
                        Description
                    </h2>
                    <p><?= $meal->description ?></p>
                </div>
                <div class="view-meal-details">
                    <?php if ($meal->image): ?>
                        <h2>
                            Image
                        </h2>
                        <img src="../../../../uploads/<?= $meal->image ?>" alt="Meal Image" style="width: 300px; height: 300px; object-fit: cover;">
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div class="view-meal-details">
                    <h2>
                        Calories
                    </h2>
                    <p><?= $meal->calories ?></p>
                </div>
                <div class="view-meal-details">
                    <h2>
                        Proteins
                    </h2>
                    <p><?= $meal->proteins ?></p>
                </div>
                <div class="view-meal-details">
                    <h2>
                        Fats
                    </h2>
                    <p><?= $meal->fats ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
