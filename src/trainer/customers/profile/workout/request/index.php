<?php
// File: src/trainer/customers/profile/workout/request/index.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../db/models/Customer.php";
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

// Get all exercises for selection
$exercises = [];
try {
    $exerciseModel = new Exercise();
    $exercises = $exerciseModel->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch exercises: " . $e->getMessage();
}

// Get customer's initial data if available
$customerGoal = "No goal set";
$fitnessLevel = "Beginner";

try {
    $conn = Database::get_conn();
    $sql = "SELECT * FROM customer_initial_data WHERE customer_id = :customer_id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':customer_id', $customerId);
    $stmt->execute();

    $initialData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($initialData) {
        // Convert goal from database code to readable text
        if (!empty($initialData['goal'])) {
            $goalFromDb = $initialData['goal'];

            if ($goalFromDb == "weight_loss") {
                $customerGoal = "Weight loss";
            } elseif ($goalFromDb == "weight_gain") {
                $customerGoal = "Weight gain";
            } elseif ($goalFromDb == "muscle_mass_gain") {
                $customerGoal = "Muscle gain";
            } elseif ($goalFromDb == "shape_body") {
                $customerGoal = "Body shaping";
            } else {
                $customerGoal = $goalFromDb; // Use as-is if not a known code
            }
        } elseif (!empty($initialData['other_goal'])) {
            $customerGoal = $initialData['other_goal'];
        }

        // Get fitness level
        $fitnessLevel = ucfirst($initialData['physical_activity_level'] ?? "Beginner");
    }
} catch (Exception $e) {
    // Just log the error, don't disrupt the flow
    error_log("Error fetching customer initial data: " . $e->getMessage());
}

$pageConfig = [
    "title" => "Request Workout",
    "navbar_active" => 1,
    "styles" => [
        "./request.css"
    ],
    "scripts" => [
        "./request.js"
    ],
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "REQUEST WORKOUT"
    ]
];

require_once "../../../../includes/header.php";
require_once "../../../../includes/titlebar.php";
?>

<main>
    <div class="customer-info">
        <h2><?= htmlspecialchars($customer->fname . ' ' . $customer->lname) ?></h2>
        <div class="customer-stats">
            <div class="stat">
                <span class="label">Goal:</span>
                <span class="value"><?= htmlspecialchars($customerGoal) ?></span>
            </div>
            <div class="stat">
                <span class="label">Fitness Level:</span>
                <span class="value"><?= htmlspecialchars($fitnessLevel) ?></span>
            </div>
        </div>
    </div>

    <form class="form" action="request_process.php" method="post">
        <input type="hidden" name="customer_id" value="<?= $customerId ?>">

        <div class="form-group">
            <label for="workout_name">Workout Name</label>
            <input type="text" id="workout_name" name="workout_name" class="form-input" required
                placeholder="Enter a descriptive name for this workout plan">
        </div>

        <div class="form-group">
            <label for="description">Workout Plan Details</label>
            <textarea id="description" name="description" class="form-textarea" rows="5" required
                placeholder="Describe the workout plan you need (client fitness level, goals, equipment access, etc.)"></textarea>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label for="workout_type">Workout Type</label>
                <select id="workout_type" name="workout_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="strength">Strength Training</option>
                    <option value="cardio">Cardio</option>
                    <option value="hiit">HIIT</option>
                    <option value="flexibility">Flexibility/Mobility</option>
                    <option value="sport">Sport Specific</option>
                    <option value="custom">Custom Program</option>
                </select>
            </div>
            <div class="form-col">
                <label for="duration">Duration (days)</label>
                <input type="number" id="duration" name="duration" class="form-input" min="1" max="365" value="30">
            </div>
        </div>

        <div class="section-title">
            <h3>Recommended Exercises</h3>
            <p class="section-description">Select exercises you'd like to include in this workout plan</p>
        </div>

        <div class="exercise-recommendations">
            <div id="exercises-container">
                <div class="exercise-item">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="exercise_id_1">Exercise</label>
                            <select name="exercise_id[]" id="exercise_id_1" class="form-select">
                                <option value="">Select an exercise</option>
                                <?php foreach ($exercises as $exercise): ?>
                                    <option value="<?= $exercise->id ?>"><?= htmlspecialchars($exercise->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-col small">
                            <label for="exercise_day_1">Day</label>
                            <input type="number" name="exercise_day[]" id="exercise_day_1" class="form-input" min="1"
                                max="7" value="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col small">
                            <label for="exercise_sets_1">Sets</label>
                            <input type="number" name="exercise_sets[]" id="exercise_sets_1" class="form-input" min="1"
                                max="20" value="3">
                        </div>
                        <div class="form-col small">
                            <label for="exercise_reps_1">Reps</label>
                            <input type="number" name="exercise_reps[]" id="exercise_reps_1" class="form-input" min="1"
                                max="100" value="10">
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
                Add Another Exercise
            </button>
        </div>

        <div class="priority-section">
            <label>Priority Level</label>
            <div class="priority-options">
                <label class="priority-option">
                    <input type="radio" name="priority" value="low">
                    <span>Low</span>
                </label>
                <label class="priority-option">
                    <input type="radio" name="priority" value="normal" checked>
                    <span>Normal</span>
                </label>
                <label class="priority-option">
                    <input type="radio" name="priority" value="high">
                    <span>High</span>
                </label>
            </div>
        </div>

        <button type="submit" class="submit-btn">Submit Workout Request</button>
    </form>

    <div class="info-box">
        <div class="info-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span>How it works</span>
        </div>
        <p>Your request will be sent to our fitness team who will create a customized workout plan. You'll receive a
            notification when it's ready to assign to your client.</p>
    </div>
</main>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>