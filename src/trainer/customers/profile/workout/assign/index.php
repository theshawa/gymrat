<?php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/Workout.php";
require_once "../../../../../db/models/Exercise.php";
require_once "../../../../../alerts/functions.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customers list
if (!$customerId) {
    redirect_with_error_alert("Invalid customer ID", "../../");
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

// Get all workout plans
$workoutPlans = [];
try {
    $workoutModel = new Workout();
    $workoutPlans = $workoutModel->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch workout plans: " . $e->getMessage();
}

// Get all exercises for custom workout creation
$exercises = [];
try {
    $exerciseModel = new Exercise();
    $exercises = $exerciseModel->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch exercises: " . $e->getMessage();
}

// Handle form submission to assign a workout plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_plan'])) {
    $workoutId = isset($_POST['workout_id']) ? intval($_POST['workout_id']) : 0;

    if ($workoutId > 0) {
        try {
            // Update customer's workout plan ID
            $sql = "UPDATE customers SET workout = :workout_id WHERE id = :customer_id";
            $conn = Database::get_conn();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':workout_id', $workoutId);
            $stmt->bindValue(':customer_id', $customerId);
            $stmt->execute();

            // Redirect with success message
            redirect_with_success_alert("Workout plan assigned successfully!", "../?id=" . $customerId);
            exit;
        } catch (Exception $e) {
            redirect_with_error_alert("Failed to assign workout plan: " . $e->getMessage(), "./assign?id=" . $customerId);
            exit;
        }
    } else {
        redirect_with_error_alert("Invalid workout plan selected", "./assign?id=" . $customerId);
        exit;
    }
}

// Handle custom workout plan creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_custom'])) {
    $planName = isset($_POST['plan_name']) ? trim($_POST['plan_name']) : '';
    $planDescription = isset($_POST['plan_description']) ? trim($_POST['plan_description']) : '';
    $planDuration = isset($_POST['plan_duration']) ? intval($_POST['plan_duration']) : 30;

    if (empty($planName) || empty($planDescription)) {
        redirect_with_error_alert("Please provide a name and description for the workout plan.", "./assign?id=" . $customerId);
        exit;
    }

    try {
        // Create new workout
        $newWorkout = new Workout();
        $newWorkout->name = $planName;
        $newWorkout->description = $planDescription;
        $newWorkout->duration = $planDuration;

        // Process exercises
        $exercisesList = [];

        if (isset($_POST['exercise_id']) && is_array($_POST['exercise_id'])) {
            $exerciseIds = $_POST['exercise_id'];
            $exerciseDays = $_POST['exercise_day'];
            $exerciseSets = $_POST['exercise_sets'];
            $exerciseReps = $_POST['exercise_reps'];

            for ($i = 0; $i < count($exerciseIds); $i++) {
                if (!empty($exerciseIds[$i])) {
                    $exercisesList[] = [
                        'id' => 0, // Will be assigned by database
                        'exercise_id' => intval($exerciseIds[$i]),
                        'day' => intval($exerciseDays[$i]),
                        'sets' => intval($exerciseSets[$i]),
                        'reps' => intval($exerciseReps[$i]),
                        'isUpdated' => true,
                        'isDeleted' => false
                    ];
                }
            }
        }

        if (empty($exercisesList)) {
            redirect_with_error_alert("Please add at least one exercise to the workout plan.", "./assign?id=" . $customerId);
            exit;
        }

        $newWorkout->exercises = $exercisesList;
        $newWorkout->save();

        // Assign the workout to the customer
        $updateSql = "UPDATE customers SET workout = :workout_id WHERE id = :customer_id";
        $conn = Database::get_conn();
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindValue(':workout_id', $newWorkout->id);
        $updateStmt->bindValue(':customer_id', $customerId);
        $updateStmt->execute();

        // Redirect with success message
        redirect_with_success_alert("Custom workout plan created and assigned successfully!", "../?id=" . $customerId);
        exit;
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to create custom workout plan: " . $e->getMessage(), "./assign?id=" . $customerId);
        exit;
    }
}

$pageConfig = [
    "title" => "Assign Workout",
    "styles" => [
        "./assign.css"
    ],
    "scripts" => [
        "./assign.js"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "ASSIGN WORKOUT"
    ]
];

require_once "../../../../includes/header.php";
require_once "../../../../includes/titlebar.php";
?>

<main class="assignment-page">
    <div class="customer-header">
        <h2>Assign Workout for <?= htmlspecialchars($customer->fname . ' ' . $customer->lname) ?></h2>
    </div>

    <div class="tabs">
        <button type="button" class="tab active" data-tab="premade-plans">Premade Plans</button>
        <button type="button" class="tab" data-tab="custom-plan">Custom Plan</button>
    </div>

    <div id="premade-plans" class="tab-content active">
        <div class="plans-list">
            <?php if (empty($workoutPlans)): ?>
                <div class="empty-state">
                    <p>No workout plans available. You'll need to create a custom plan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($workoutPlans as $plan): ?>
                    <div class="plan-card">
                        <div class="plan-header">
                            <h3><?= htmlspecialchars($plan->name) ?></h3>
                            <span class="duration"><?= $plan->duration ?> days</span>
                        </div>

                        <p class="plan-description"><?= htmlspecialchars($plan->description) ?></p>

                        <div class="exercise-count">
                            <span class="count-label">Exercises:</span>
                            <span class="count-value"><?= count($plan->exercises) ?></span>
                        </div>

                        <div class="plan-actions">
                            <form method="POST" action="">
                                <input type="hidden" name="workout_id" value="<?= $plan->id ?>">
                                <button type="submit" name="assign_plan" class="btn assign-btn">Assign</button>
                            </form>

                            <a href="./preview?id=<?= $plan->id ?>&customer_id=<?= $customerId ?>"
                                class="btn preview-btn">Preview</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="custom-plan" class="tab-content">
        <form method="POST" action="" id="custom-workout-form">
            <input type="hidden" name="create_custom" value="1">

            <div class="form-group">
                <label class="form-label" for="plan_name">Workout Name</label>
                <input type="text" id="plan_name" name="plan_name" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="plan_description">Description</label>
                <textarea id="plan_description" name="plan_description" class="form-textarea" required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="plan_duration">Duration (days)</label>
                <input type="number" id="plan_duration" name="plan_duration" class="form-input" min="1" max="90"
                    value="30" required>
            </div>

            <h3>Exercises</h3>

            <div id="exercises-container">
                <!-- First exercise form fields -->
                <div class="exercise-item">
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label" for="exercise_id_0">Exercise</label>
                            <select name="exercise_id[]" id="exercise_id_0" class="form-select" required>
                                <option value="">Select an exercise</option>
                                <?php foreach ($exercises as $exercise): ?>
                                    <option value="<?= $exercise->id ?>"><?= htmlspecialchars($exercise->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-col small">
                            <label class="form-label" for="exercise_day_0">Day</label>
                            <input type="number" name="exercise_day[]" id="exercise_day_0" class="form-input" min="1"
                                max="7" value="1" required>
                        </div>
                        <button type="button" class="remove-btn" onclick="removeExercise(this)"
                            title="Remove exercise">×</button>
                    </div>
                    <div class="form-row">
                        <div class="form-col small">
                            <label class="form-label" for="exercise_sets_0">Sets</label>
                            <input type="number" name="exercise_sets[]" id="exercise_sets_0" class="form-input" min="1"
                                max="20" value="3" required>
                        </div>
                        <div class="form-col small">
                            <label class="form-label" for="exercise_reps_0">Reps</label>
                            <input type="number" name="exercise_reps[]" id="exercise_reps_0" class="form-input" min="1"
                                max="100" value="10" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="add-exercise-btn" onclick="addExercise()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Exercise
            </button>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn assign-btn">Create & Assign</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>