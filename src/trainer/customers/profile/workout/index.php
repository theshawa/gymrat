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
        }
    } catch (Exception $e) {
        $_SESSION['workout_error'] = "Error loading workout: " . $e->getMessage();
    }
}

// Configure the page
$pageConfig = [
    "title" => "Current Workout",
    "styles" => [
        "./work.css"
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
                <a href="./assign?id=<?= $customerId ?>" class="btn assign-btn">Assign Workout</a>
                <a href="../../workouts/request/" class="btn request-btn">Request Custom Workout</a>
            </div>
        </div>
    <?php else: ?>
        <div class="workout-info">
            <h3><?= htmlspecialchars($workout->name) ?></h3>
            <p class="workout-description"><?= htmlspecialchars($workout->description) ?></p>
            <p class="workout-duration">Duration: <?= intval($workout->duration) ?> days</p>
        </div>

        <div class="exercise-list">
            <?php if (empty($workout->exercises)): ?>
                <div class="no-exercises-message">
                    <p>No exercises defined in this workout plan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($workout->exercises as $exercise): ?>
                    <div class="exercise-item">
                        <div class="exercise-details">
                            <span
                                class="exercise-name"><?= htmlspecialchars($exercise['title'] ?? $exercise['name'] ?? 'Unknown Exercise') ?></span>
                            <span class="exercise-sets"><?= $exercise['sets'] ?> x <?= $exercise['reps'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="./assign?id=<?= $customerId ?>" class="btn change-btn">Change Workout</a>
        </div>
    <?php endif; ?>
</main>

<style>
    .no-workout-message,
    .no-exercises-message {
        background-color: #18181B;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .no-workout-message p,
    .no-exercises-message p {
        color: #A1A1AA;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
    }

    .assign-btn,
    .change-btn {
        background-color: #6700e6;
        color: white;
    }

    .request-btn {
        background-color: #3F3F46;
        color: white;
    }
</style>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>