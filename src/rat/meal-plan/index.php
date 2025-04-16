<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "./data.php";


$pageConfig = [
    "title" => "My Meal Plan",
    "styles" => ["./meal-plan.css"],
    "titlebar" => [
        "title" => "My Meal Plan",
        "back_url" => "../"
    ],
    "navbar_active" => 1
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <div class="banner" style="background-image: radial-gradient(100% 100% at 50.13% 0%, rgba(9, 9, 11, 0.00) 0%, rgba(9, 9, 11, 0.90) 100%), url(<?= $mealPlan['image'] ?>)">
        <div class="left">
            <h1><?= $mealPlan['title'] ?></h1>
            <p class="paragraph" style="max-width: 200px;"><?= $mealPlan['description'] ?></p>
        </div>
        <span class="calories"><?= $mealPlan['calories'] ?> Calories</span>
    </div>
    <div class="description">
        <h3>How to Use</h3>
        <p class="paragraph"><?= $mealPlan['usage'] ?></p>
    </div>
    <div class="meals">
        <?php foreach ($mealPlan['meals'] as $i => $meal): ?>
            <div class="meal">
                <button class="title">
                    <div class="left">
                        <span>Meal <?= $i + 1 ?></span>
                        <h3><?= $meal['mealName'] ?></h3>
                    </div>
                    <span class="calories"><?= $meal['totalCalories'] ?> calories</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="cavet">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.64645 5.14645C7.84171 4.95118 8.15829 4.95118 8.35355 5.14645L13.3536 10.1464C13.5488 10.3417 13.5488 10.6583 13.3536 10.8536C13.1583 11.0488 12.8417 11.0488 12.6464 10.8536L8 6.20711L3.35355 10.8536C3.15829 11.0488 2.84171 11.0488 2.64645 10.8536C2.45118 10.6583 2.45118 10.3417 2.64645 10.1464L7.64645 5.14645Z" fill="#71717A" />
                    </svg>
                </button>
                <div class="meal-items-wrapper">
                    <div class="meal-items">
                        <?php foreach ($meal['items'] as $item): ?>
                            <div class="meal-item">
                                <img src="<?= $item['image'] ?>" alt="Image of meal item <?= $item['name'] ?>" class="featured-image">
                                <div class="right">
                                    <h4><?= $item['name'] ?></h4>
                                    <span class="calories"><?= $item['calories'] ?> calories</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="description">
        <h3>Notes for Customization</h3>
        <p class="paragraph">Adjust portion sizes to fit your activity level or calorie goals, space meals to match your schedule, and drink 2-3 liters of water daily. Swap ingredients of similar calories for variety while keeping the nutritional balance.</p>
    </div>
    <!-- <a class="btn" style="width: 100%;margin-top:20px;" href="request_custom_meal_plan_process.php">Request Custom Meal Plan</a> -->
</main>

<script>
    document.querySelectorAll(".meal").forEach((meal) => {
        const toggler = meal.querySelector("button");

        meal.addEventListener("click", () => {
            meal.classList.toggle("hidden");
        });
    });
</script>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>