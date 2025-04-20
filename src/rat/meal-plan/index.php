<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Customer.php";
$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $user->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get user: " . $th->getMessage());
}

if (!$user->meal_plan) {
    die("You don't have a meal plan. Please contact your trainer to get one.");
}

require_once "../../db/models/MealPlan.php";
$mealPlan = new MealPlan();
try {
    $mealPlan->get_by_id($user->meal_plan);
} catch (\Throwable $th) {
    die("Failed to get meal plan: " . $th->getMessage());
}

if (empty($mealPlan->meals)) {
    die("This meal plan has no meals. Please contact your trainer to get a new one.");
}


$total_calories = 0;


require_once "../../db/models/Meal.php";

$meals = [];
$total_calories = 0;

foreach ($mealPlan->meals as $meal_ref) {
    try {
        $meal = new Meal();
        $meal->get_by_id($meal_ref['meal_id']);
        $total_calories += $meal->calories;
        if (array_key_exists($meal_ref['day'], $meals)) {
            $current_day_meals = $meals[$meal_ref['day']];
            if (array_key_exists($meal_ref['time'], $current_day_meals)) {
                $meals[$meal_ref['day']][$meal_ref['time']][] = $meal;
            } else {
                $meals[$meal_ref['day']][$meal_ref['time']] = [$meal];
            }
        } else {
            $meals[$meal_ref['day']] = [
                $meal_ref['time'] => [$meal]
            ];
        }
    } catch (\Throwable $th) {
        die("Failed to get meal: " . $th->getMessage());
    }
}

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
    <?php require_once "../../uploads.php" ?>
    <div class="banner" style="background-image: radial-gradient(100% 100% at 50.13% 0%, rgba(9, 9, 11, 0.00) 0%, rgba(9, 9, 11, 0.90) 100%), url(<?= get_file_url("default-images/meal_plan_banner.avif") ?>)">
        <div class="left">
            <h1><?= $mealPlan->name ?></h1>
            <p class="paragraph" style="max-width: 200px;"><?= $mealPlan->description ?></p>
        </div>
        <span class="calories"><?= $total_calories ?> Calories</span>
    </div>
    <!-- <div class="description">
        <h3>How to Use</h3>
        <p class="paragraph"></p>
    </div> -->
    <div class="meals">
        <?php foreach ($meals as $day => $day_meals): ?>
            <?php
            $total_day_calories = 0;
            foreach ($day_meals as $time => $meal_items) {
                foreach ($meal_items as $meal) {
                    $total_day_calories += $meal->calories;
                }
            }
            ?>
            <div class="meal">
                <button class="title">
                    <div class="left">
                        <h3><?= $day ?></h3>
                    </div>
                    <span class="calories"><?= $total_day_calories ?> calories</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="cavet">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.64645 5.14645C7.84171 4.95118 8.15829 4.95118 8.35355 5.14645L13.3536 10.1464C13.5488 10.3417 13.5488 10.6583 13.3536 10.8536C13.1583 11.0488 12.8417 11.0488 12.6464 10.8536L8 6.20711L3.35355 10.8536C3.15829 11.0488 2.84171 11.0488 2.64645 10.8536C2.45118 10.6583 2.45118 10.3417 2.64645 10.1464L7.64645 5.14645Z" fill="#71717A" />
                    </svg>
                </button>
                <div class="meal-items-wrapper">
                    <div class="meal-items">
                        <?php foreach ($day_meals as $time => $meals): ?>
                            <div class="meal-time">
                                <h4><?= $time ?></h4>
                                <div class="meal-items-list">
                                    <?php foreach ($meals as $meal): ?>
                                        <div class="meal-item">
                                            <div class="top">
                                                <img src="<?= empty($meal->image) ? get_file_url("default-images/default-meal.png") :  get_file_url($meal->image)
                                                            ?>" alt="Image of meal item <?= $meal->name ?>" class="featured-image">
                                                <div class="right">
                                                    <h4><?= $meal->name ?></h4>
                                                    <div class="facts">
                                                        <span class="fact"><?= $meal->calories ?> calories</span>
                                                        <span class="fact"><?= $meal->proteins ?> proteins</span>
                                                        <span class="fact"><?= $meal->fats ?> fat</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="paragraph small description">
                                                <?= $meal->description ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="info">
        <h3>Notes for Customization</h3>
        <p class="paragraph">Adjust portion sizes to fit your activity level or calorie goals, space meals to match your schedule, and drink 2-3 liters of water daily. Swap ingredients of similar calories for variety while keeping the nutritional balance.</p>
    </div>
    <!-- <a class="btn" style="width: 100%;margin-top:20px;" href="request_custom_meal_plan_process.php">Request Custom Meal Plan</a> -->
</main>

<script>
    document.querySelectorAll(".meal").forEach((meal) => {
        const toggler = meal.querySelector("button");

        toggler.addEventListener("click", () => {
            meal.classList.toggle("hidden");
        });
    });
</script>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>