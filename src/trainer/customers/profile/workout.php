<?php
// File path: src/trainer/customers/profile/workout.php
require_once "../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;


// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customer list
if (!$customerId) {
    header("Location: ../");
    exit;
}

// Check if there's a request edit submission
if (isset($_POST['request_edit'])) {
    // In a real system, this would create a record in a database
    // For now, just set a session variable to simulate it
    $_SESSION['edit_requested'] = true;

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// NOTE: In the future, this would be fetched from a database
// For now, we'll use a mock workout data structure
// This could be replaced with a database query like:
// $workoutModel = new WorkoutModel();
// $workout = $workoutModel->getCustomerWorkout($customerId);

// Mock data for the customer's workout
$workout = [
    'id' => 123,
    'name' => 'Full Body Strength',
    'exercises' => [
        [
            'name' => 'Power Squats',
            'sets' => 3,
            'reps' => 4
        ],
        [
            'name' => 'Weight Lifting',
            'sets' => 6,
            'reps' => 4
        ],
        [
            'name' => 'Belly Push',
            'sets' => 12,
            'reps' => 4
        ],
        [
            'name' => 'Arm Swing',
            'sets' => 8,
            'reps' => 4
        ]
    ]
];

$pageConfig = [
    "title" => "Current Workout",
    "styles" => [
        "./workout.css" // The CSS file we'll create
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "./index.php?id=" . $_GET['id'],
        "title" => "CURRENT WORKOUT"
    ]
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="workout-page">
    <div class="top-action">
        <form method="post" action="">
            <button type="submit" name="request_edit" class="request-edit-btn">
                Request To Edit
            </button>
        </form>
    </div>

    <?php if (isset($_SESSION['edit_requested']) && $_SESSION['edit_requested']): ?>
        <div class="notification">
            <p>Edit request sent successfully!</p>
        </div>
        <?php
        // Clear the notification after showing it once
        unset($_SESSION['edit_requested']);
        ?>
    <?php endif; ?>

    <div class="exercise-list">
        <?php foreach ($workout['exercises'] as $exercise): ?>
            <div class="exercise-item">
                <div class="exercise-details">
                    <span class="exercise-name"><?= htmlspecialchars($exercise['name']) ?></span>
                    <span class="exercise-sets"><?= $exercise['sets'] ?> x <?= $exercise['reps'] ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>