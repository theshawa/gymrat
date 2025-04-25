<?php
// File: src/trainer/customers/profile/workout/index.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

// Include database models
require_once __DIR__ . "/../../../../db/models/Workout.php";
require_once __DIR__ . "/../../../../db/models/Customer.php";
require_once __DIR__ . "/../../../../db/models/Exercise.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customer list
if (!$customerId) {
    header("Location: ../../");
    exit;
}

// Get customer data
$customer = new Customer();
$customer->id = $customerId;
try {
    $customer->get_by_id();
} catch (Exception $e) {
    die("Error fetching customer: " . $e->getMessage());
}

// Get the customer's workout from the database
$workout = null;
$workoutId = $customer->workout;
$exerciseModel = new Exercise();

if ($workoutId) {
    try {
        $workout = new Workout();
        $workout->get_by_id($workoutId);

        // Get exercise titles for display
        if (!empty($workout->exercises)) {
            $workout->exercises = $exerciseModel->addExerciseTitles($workout->exercises);

            // Group exercises by day for better display
            $exercisesByDay = [];
            foreach ($workout->exercises as $exercise) {
                $day = $exercise['day'];
                if (!isset($exercisesByDay[$day])) {
                    $exercisesByDay[$day] = [];
                }
                $exercisesByDay[$day][] = $exercise;
            }
            // Sort days numerically
            ksort($exercisesByDay);
        }
    } catch (Exception $e) {
        $_SESSION['workout_error'] = "Error loading workout: " . $e->getMessage();
    }
}

// Configure the page
$pageConfig = [
    "title" => "Current Workout",
    "styles" => [
        "./workouts.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "CURRENT WORKOUT"
    ]
];

// Include header files
require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main class="workout-page">
    <?php if (isset($_SESSION['workout_error'])): ?>
        <div class="notification error">
            <p><?= $_SESSION['workout_error'] ?></p>
        </div>
        <?php unset($_SESSION['workout_error']); ?>
    <?php endif; ?>

    <?php if (!$workout): ?>
        <div class="no-workout-message">
            <p>No workout plan is currently assigned to this customer.</p>
            <div class="action-buttons">
                <a href="./assign?id=<?= $customerId ?>" class="btn primary-btn">Assign Workout</a>
                <a href="./request/?id=<?= $customerId ?>" class="btn secondary-btn">Custom Workout</a>
            </div>
        </div>
    <?php else: ?>
        <div class="workout-info">
            <h2><?= htmlspecialchars($workout->name) ?></h2>
            <p class="workout-description"><?= htmlspecialchars($workout->description) ?></p>
            <span class="workout-duration">Duration: <?= intval($workout->duration) ?> days</span>
        </div>

        <?php if (empty($workout->exercises)): ?>
            <div class="no-exercises-message">
                <p>No exercises defined in this workout plan.</p>
            </div>
        <?php else: ?>
            <div class="exercise-days">
                <?php foreach ($exercisesByDay as $day => $exercises): ?>
                    <div class="day-section">
                        <h3>Day <?= $day ?></h3>
                        <div class="exercise-list">
                            <?php foreach ($exercises as $exercise): ?>
                                <div class="exercise-item">
                                    <div class="exercise-details">
                                        <span
                                            class="exercise-name"><?= htmlspecialchars($exercise['title'] ?? $exercise['name'] ?? 'Unknown Exercise') ?></span>
                                        <span class="exercise-data"><?= $exercise['sets'] ?> × <?= $exercise['reps'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="./assign?id=<?= $customerId ?>" class="btn secondary-btn">Change Workout</a>
            <a href="./request/?id=<?= $customerId ?>" class="btn secondary-btn">Custom Workout</a>
        </div>
    <?php endif; ?>
</main>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>