<?php
// File path: src/trainer/customers/profile/workout/index.php

// Disable notices and deprecation warnings for this page only
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// Start session only if one doesn't exist already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database models
require_once __DIR__ . "/../../../../db/models/Workout.php";
require_once __DIR__ . "/../../../../db/models/Customer.php";
require_once __DIR__ . "/../../../../db/models/Exercise.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if we're in edit mode
$editMode = isset($_GET['edit']) && $_GET['edit'] === 'true';

// If customer ID is missing, redirect back to customer list
if (!$customerId) {
    header("Location: ../../");
    exit;
}

// Verify if the customer exists in the database
// This is important for database operations that reference the customer
$customerExists = false;
try {
    $customerModel = new Customer();
    $customerModel->id = $customerId;
    $customerModel->get_by_id();
    $customerExists = true;
} catch (Exception $e) {
    // Customer doesn't exist in the database
    // We'll use session storage as a fallback
}

// Process form submission for saving workout changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_workout'])) {
    $workoutId = isset($_POST['workout_id']) ? intval($_POST['workout_id']) : 0;

    try {
        // Always create a new workout to ensure customer-specific changes
        $workout = new Workout();

        // If editing an existing workout, copy its base properties
        if ($workoutId > 0) {
            $originalWorkout = new Workout();
            try {
                $originalWorkout->get_by_id($workoutId);
                // Copy basic properties, but we'll use the new exercises
                $workout->name = $originalWorkout->name;
                $workout->description = "Custom version of " . $originalWorkout->name . " for customer #" . $customerId;
                $workout->duration = $originalWorkout->duration;
            } catch (Exception $e) {
                // If original not found, use form values
                $workout->name = isset($_POST['workout_name']) ? $_POST['workout_name'] : 'Custom Workout';
                $workout->description = "Custom workout for customer #" . $customerId;
                $workout->duration = isset($_POST['workout_duration']) ? intval($_POST['workout_duration']) : 30;
            }
        } else {
            // New workout, use form values
            $workout->name = isset($_POST['workout_name']) ? $_POST['workout_name'] : 'Custom Workout';
            $workout->description = "Custom workout for customer #" . $customerId;
            $workout->duration = isset($_POST['workout_duration']) ? intval($_POST['workout_duration']) : 30;
        }

        // Get the submitted exercises
        $exercises = [];

        if (isset($_POST['exercise_name']) && is_array($_POST['exercise_name'])) {
            $count = count($_POST['exercise_name']);

            for ($i = 0; $i < $count; $i++) {
                if (!empty($_POST['exercise_name'][$i])) {
                    // For each exercise, create a new entry (not update existing ones)
                    $exercises[] = [
                        'id' => 0, // Force new exercise record
                        'exercise_id' => isset($_POST['exercise_id'][$i]) ? intval($_POST['exercise_id'][$i]) : 0,
                        'title' => $_POST['exercise_name'][$i],
                        'sets' => (int) $_POST['exercise_sets'][$i],
                        'reps' => (int) $_POST['exercise_reps'][$i],
                        'isUpdated' => false, // New exercises, not updates
                        'isDeleted' => false
                    ];
                }
            }
        }

        // Update workout exercises
        $workout->exercises = $exercises;

        // Save the NEW workout
        $workout->create();

        // Store the new workout in the session for this customer
        // This is our fallback mechanism when customers don't exist in database
        $_SESSION['customer_workout_' . $customerId] = [
            'id' => $workout->id,
            'name' => $workout->name,
            'description' => $workout->description,
            'duration' => $workout->duration,
            'exercises' => $workout->exercises
        ];

        // Set success message
        $_SESSION['workout_updated'] = true;
        $_SESSION['new_workout_id'] = $workout->id;
    } catch (Exception $e) {
        $_SESSION['workout_error'] = "Error saving workout: " . $e->getMessage();
    }

    // Redirect back to view mode
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $customerId);
    exit;
}

// Configure the page
$pageConfig = [
    "title" => "Current Workout",
    "styles" => [
        "./workout.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => $editMode ? $_SERVER['PHP_SELF'] . "?id=" . $customerId : "../?id=" . $customerId,
        "title" => $editMode ? "EDIT WORKOUT" : "CURRENT WORKOUT"
    ],
    "need_auth" => true
];

// Include header files
require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";

// Get the customer's workout from the database
$workout = null;
$exerciseModel = new Exercise();

try {
    // Create default workout data structure
    $defaultWorkout = new Workout();
    $defaultWorkout->id = 0;
    $defaultWorkout->name = "Default Workout";
    $defaultWorkout->description = "Default workout for customer";
    $defaultWorkout->duration = 30;
    $defaultWorkout->exercises = [
        [
            'id' => 1,
            'exercise_id' => 0,
            'title' => 'Power Squats',
            'sets' => 3,
            'reps' => 4
        ],
        [
            'id' => 2,
            'exercise_id' => 0,
            'title' => 'Weight Lifting',
            'sets' => 6,
            'reps' => 4
        ],
        [
            'id' => 3,
            'exercise_id' => 0,
            'title' => 'Belly Push',
            'sets' => 12,
            'reps' => 4
        ],
        [
            'id' => 4,
            'exercise_id' => 0,
            'title' => 'Arm Swing',
            'sets' => 8,
            'reps' => 4
        ]
    ];

    // Check for a session-stored workout first
    $sessionKey = 'customer_workout_' . $customerId;
    if (
        isset($_SESSION[$sessionKey]) &&
        is_array($_SESSION[$sessionKey]) &&
        isset($_SESSION[$sessionKey]['exercises']) &&
        !empty($_SESSION[$sessionKey]['exercises'])
    ) {

        // Use the session-stored workout
        $sessionWorkout = $_SESSION[$sessionKey];
        $workout = new Workout();
        $workout->id = $sessionWorkout['id'];
        $workout->name = $sessionWorkout['name'];
        $workout->description = $sessionWorkout['description'];
        $workout->duration = $sessionWorkout['duration'];
        $workout->exercises = $sessionWorkout['exercises'];
    } else {
        // No session workout, try to get from database
        try {
            // For now, try to get a workout with ID matching customerId (just for demonstration)
            // In a real app, you'd have a proper lookup mechanism
            if ($customerId > 0) {
                $workout = new Workout();
                $workout->get_by_id($customerId);

                // Get exercise titles for display
                if (!empty($workout->exercises)) {
                    $workout->exercises = $exerciseModel->addExerciseTitles($workout->exercises);
                } else {
                    throw new Exception("No exercises found in the workout");
                }
            } else {
                throw new Exception("Invalid customer ID");
            }
        } catch (Exception $e) {
            // If no workout found or error, use default
            $workout = $defaultWorkout;
        }
    }
} catch (Exception $e) {
    // Use default workout if there's any error
    $workout = $defaultWorkout;
    $_SESSION['workout_error'] = "Error loading workout: " . $e->getMessage();
}

// Get all exercises for dropdown
$allExercises = [];
try {
    $allExercises = $exerciseModel->get_all_titles();
} catch (Exception $e) {
    // Silently fail
}
?>

<main class="workout-page">
    <?php if (isset($_SESSION['workout_error'])): ?>
        <div class="notification error">
            <p><?= $_SESSION['workout_error'] ?></p>
        </div>
        <?php unset($_SESSION['workout_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['workout_updated']) && $_SESSION['workout_updated']): ?>
        <div class="notification success">
            <p>Workout updated successfully! Created personalized workout for this customer.</p>
        </div>
        <?php unset($_SESSION['workout_updated']);
        unset($_SESSION['new_workout_id']); ?>
    <?php endif; ?>

    <?php if ($editMode): ?>
        <!-- EDIT MODE: Show editable form -->
        <form method="post" action="" id="workout-form">
            <input type="hidden" name="workout_id" value="<?= $workout->id ?>">
            <input type="hidden" name="workout_name" value="<?= htmlspecialchars($workout->name) ?>">
            <input type="hidden" name="workout_duration" value="<?= $workout->duration ?>">

            <div class="top-action">
                <button type="submit" name="save_workout" class="request-edit-btn save-btn">
                    Save Changes
                </button>
                <a href="?id=<?= $customerId ?>" class="request-edit-btn cancel-btn">
                    Cancel
                </a>
            </div>

            <div class="exercise-list">
                <?php foreach ($workout->exercises as $index => $exercise): ?>
                    <div class="exercise-item editable">
                        <div class="exercise-details">
                            <input type="hidden" name="exercise_id[]" value="<?= $exercise['exercise_id'] ?? 0 ?>">
                            <input type="text" name="exercise_name[]"
                                value="<?= htmlspecialchars($exercise['title'] ?? $exercise['name'] ?? '') ?>"
                                class="exercise-input" placeholder="Exercise name" required>

                            <div class="sets-reps-inputs">
                                <input type="number" name="exercise_sets[]" value="<?= $exercise['sets'] ?>" class="sets-input"
                                    min="1" max="50" required>
                                <span>x</span>
                                <input type="number" name="exercise_reps[]" value="<?= $exercise['reps'] ?>" class="reps-input"
                                    min="1" max="200" required>
                            </div>

                            <button type="button" class="remove-exercise"
                                onclick="removeExercise(this.parentNode.parentNode)">×</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" id="add-exercise" class="add-exercise-btn">
                + Add Exercise
            </button>
        </form>
    <?php else: ?>
        <!-- VIEW MODE: Show read-only list -->
        <div class="top-action">
            <a href="?id=<?= $customerId ?>&edit=true" class="request-edit-btn">
                Request To Edit
            </a>
        </div>

        <div class="workout-info">
            <h3><?= htmlspecialchars($workout->name) ?></h3>
            <p class="workout-description"><?= htmlspecialchars($workout->description) ?></p>
            <p class="workout-duration">Duration: <?= intval($workout->duration) ?> days</p>
        </div>

        <div class="exercise-list">
            <?php if (empty($workout->exercises)): ?>
                <div class="notification">
                    <p>No exercises in this workout yet.</p>
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
    <?php endif; ?>
</main>

<!-- Add JavaScript for edit mode functionality -->
<?php if ($editMode): ?>
    <script>
        // Function to remove an exercise
        function removeExercise(exerciseEl) {
            exerciseEl.remove();

            // Check if we have any exercises left
            const exerciseItems = document.querySelectorAll('.exercise-item.editable');
            if (exerciseItems.length === 0) {
                // Add a new empty exercise if we removed the last one
                document.getElementById('add-exercise').click();
            }
        }

        // Function to add a new exercise
        document.getElementById('add-exercise').addEventListener('click', function () {
            const exerciseList = document.querySelector('.exercise-list');

            // Create new exercise item
            const newExercise = document.createElement('div');
            newExercise.className = 'exercise-item editable';

            // Add HTML content
            newExercise.innerHTML = `
            <div class="exercise-details">
                <input type="hidden" name="exercise_id[]" value="0">
                <input type="text" name="exercise_name[]" class="exercise-input" placeholder="Exercise name" required>
                
                <div class="sets-reps-inputs">
                    <input type="number" name="exercise_sets[]" value="3" class="sets-input" min="1" max="50" required>
                    <span>x</span>
                    <input type="number" name="exercise_reps[]" value="10" class="reps-input" min="1" max="200" required>
                </div>
                
                <button type="button" class="remove-exercise" onclick="removeExercise(this.parentNode.parentNode)">×</button>
            </div>
        `;

            // Add to the list
            exerciseList.appendChild(newExercise);

            // Focus the newly added input
            const newInput = newExercise.querySelector('.exercise-input');
            if (newInput) {
                newInput.focus();
            }
        });
    </script>
<?php endif; ?>

<style>
    .workout-info {
        background-color: #27272A;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .workout-info h3 {
        margin-top: 0;
        font-size: 18px;
        color: #FFFFFF;
    }

    .workout-description {
        color: #D4D4D8;
        font-size: 14px;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .workout-duration {
        color: #A1A1AA;
        font-size: 14px;
        margin: 0;
    }

    /* Add error notification styling */
    .notification.error {
        background-color: rgba(213, 58, 58, 0.2);
        border: 1px solid #D53A3A;
    }
</style>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>