<?php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/Workout.php";
require_once "../../../../../db/models/WorkoutRequest.php";
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

// Get approved custom workout plans
$customWorkouts = [];
try {
    $conn = Database::get_conn();
    $sql = "SELECT wr.id, wr.description, wr.reviewed FROM workout_requests wr 
            WHERE wr.trainer_id = :trainer_id AND wr.reviewed = 1
            ORDER BY wr.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $_SESSION['auth']['id']);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($requests as $request) {
        // Try to extract the workout details from the JSON part of the description
        $description = $request['description'];
        $jsonEnd = strpos($description, "\n\n");
        if ($jsonEnd !== false) {
            try {
                $jsonPart = substr($description, 0, $jsonEnd);
                $details = json_decode($jsonPart, true);

                if ($details && isset($details['name'])) {
                    // Extract the plain text part too
                    $textPart = substr($description, $jsonEnd + 2);

                    $customWorkouts[] = [
                        'id' => $request['id'],
                        'name' => $details['name'],
                        'type' => $details['type'] ?? 'custom',
                        'duration' => $details['duration'] ?? 30,
                        'description' => $details['description'] ?? '',
                        'exercises' => $details['exercises'] ?? [],
                        'full_description' => $textPart,
                        'is_custom' => true
                    ];
                }
            } catch (Exception $e) {
                // If JSON parsing fails, just use the raw description
                $lines = explode("\n", $description);
                $name = '';

                // Try to extract name from the first lines
                foreach ($lines as $line) {
                    if (strpos($line, 'Name:') === 0) {
                        $name = trim(substr($line, 5));
                        break;
                    }
                }

                if (empty($name)) {
                    $name = "Custom Workout #" . $request['id'];
                }

                $customWorkouts[] = [
                    'id' => $request['id'],
                    'name' => $name,
                    'description' => $description,
                    'is_custom' => true
                ];
            }
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch custom workout plans: " . $e->getMessage();
}

// Handle form submission to assign a workout plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_plan'])) {
    $workoutId = isset($_POST['workout_id']) ? intval($_POST['workout_id']) : 0;
    $isCustom = isset($_POST['is_custom']) && $_POST['is_custom'] === 'true';

    if ($workoutId > 0) {
        try {
            if ($isCustom) {
                // For custom workouts, we need to first create a new workout from the request
                // Get the custom workout request details
                $requestModel = new WorkoutRequest();
                $requestModel->fill(['id' => $workoutId]);
                $requestModel->get_by_id($workoutId);

                // Parse the description to get workout details
                $description = $requestModel->description;
                $jsonEnd = strpos($description, "\n\n");
                $details = null;

                if ($jsonEnd !== false) {
                    try {
                        $jsonPart = substr($description, 0, $jsonEnd);
                        $details = json_decode($jsonPart, true);
                    } catch (Exception $e) {
                        // Failed to parse JSON, fallback to text parsing
                        $details = null;
                    }
                }

                if (!$details) {
                    // Try to extract data from the text description
                    $lines = explode("\n", $description);
                    $details = [
                        'name' => 'Custom Workout',
                        'description' => $description,
                        'duration' => 30,
                        'exercises' => []
                    ];

                    foreach ($lines as $line) {
                        if (strpos($line, 'Name:') === 0) {
                            $details['name'] = trim(substr($line, 5));
                        } elseif (strpos($line, 'Duration:') === 0) {
                            $durationParts = explode(' ', trim(substr($line, 9)));
                            if (isset($durationParts[0]) && is_numeric($durationParts[0])) {
                                $details['duration'] = intval($durationParts[0]);
                            }
                        }
                    }
                }

                // Create a new workout
                $newWorkout = new Workout();
                $newWorkout->name = $details['name'];
                $newWorkout->description = $details['description'];
                $newWorkout->duration = $details['duration'];

                // Add exercises if available
                $exercisesToAdd = [];
                if (isset($details['exercises']) && is_array($details['exercises'])) {
                    foreach ($details['exercises'] as $exercise) {
                        if (isset($exercise['id'])) {
                            $exercisesToAdd[] = [
                                'exercise_id' => $exercise['id'],
                                'day' => $exercise['day'] ?? 1,
                                'sets' => $exercise['sets'] ?? 3,
                                'reps' => $exercise['reps'] ?? 10,
                                'isUpdated' => true
                            ];
                        }
                    }
                }

                $newWorkout->exercises = $exercisesToAdd;
                $newWorkout->save();

                // Now assign the new workout to the customer
                $workoutId = $newWorkout->id;
            }

            // Update customer's workout plan ID
            $sql = "UPDATE customers SET workout = :workout_id WHERE id = :customer_id";
            $conn = Database::get_conn();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':workout_id', $workoutId);
            $stmt->bindValue(':customer_id', $customerId);
            $stmt->execute();

            // Send notification to the customer
            if (function_exists('notify_rat')) {
                notify_rat(
                    $customerId,
                    "New Workout Plan Assigned",
                    "Your trainer has assigned you a new workout plan. Check it out in your workouts section!"
                );
            }

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

$pageConfig = [
    "title" => "Assign Workout",
    "styles" => [
        "./assign.css"
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
    <?php if (!empty($customWorkouts)): ?>
        <div class="section-header">
            <h3 class="section-title">Custom Workout Plans</h3>
            <p class="section-subtitle">These custom workout plans have been approved and are ready to assign</p>
        </div>

        <div class="plans-list custom-plans">
            <?php foreach ($customWorkouts as $plan): ?>
                <div class="plan-card custom-plan">
                    <div class="plan-header">
                        <h3><?= htmlspecialchars($plan['name']) ?></h3>
                        <span class="duration"><?= $plan['duration'] ?? 30 ?> days</span>
                    </div>

                    <p class="plan-description">
                        <?= htmlspecialchars(substr($plan['description'], 0, 150)) ?>
                        <?= strlen($plan['description']) > 150 ? '...' : '' ?>
                    </p>

                    <?php if (isset($plan['exercises']) && is_array($plan['exercises'])): ?>
                        <div class="exercise-count">
                            <span class="count-label">Exercises:</span>
                            <span class="count-value"><?= count($plan['exercises']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="plan-actions">
                        <form method="POST" action="">
                            <input type="hidden" name="workout_id" value="<?= $plan['id'] ?>">
                            <input type="hidden" name="is_custom" value="true">
                            <button type="submit" name="assign_plan" class="btn secondary-btn">Assign</button>
                        </form>

                        <!-- <button type="button" class="btn preview-btn"
                            onclick="showCustomPlanDetails(<?= htmlspecialchars(json_encode($plan)) ?>)">
                            Preview
                        </button> -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="section-header">
        <h3 class="section-title">Standard Workout Plans</h3>
        <p class="section-subtitle">Pre-designed workout plans for various fitness goals</p>
    </div>

    <div class="plans-list standard-plans">
        <?php if (empty($workoutPlans)): ?>
            <div class="empty-state">
                <p>No standard workout plans available. You can request a custom plan instead.</p>
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
                            <input type="hidden" name="is_custom" value="false">
                            <button type="submit" name="assign_plan" class="btn secondary-btn">Assign</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Request Custom Workout Section -->
    <div class="request-container">
        <p style="padding-bottom: 10px;">Don't see a suitable plan?</p>
        <a href="../request?id=<?= $customerId ?>" class="btn secondary-btn">Request Custom Workout</a>
    </div>
</main>

<!-- Modal for displaying custom workout details -->
<div id="customPlanModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Custom Workout Plan</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div id="modalDescription"></div>
            <div id="modalExercises" class="modal-exercises"></div>
        </div>
    </div>
</div>

<script>
    // Function to display custom plan details in modal
    function showCustomPlanDetails(plan) {
        const modal = document.getElementById('customPlanModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalExercises = document.getElementById('modalExercises');

        // Set the plan details
        modalTitle.textContent = plan.name;

        // Use the full description if available
        if (plan.full_description) {
            modalDescription.innerHTML = plan.full_description
                .replace(/\n/g, '<br>')
                .replace(/^(.*?):\s*(.*?)$/gm, '<p><strong>$1:</strong> $2</p>');
        } else {
            modalDescription.innerHTML = `<p>${plan.description.replace(/\n/g, '<br>')}</p>`;
        }

        // Clear previous exercises
        modalExercises.innerHTML = '';

        // Add exercises if available
        if (plan.exercises && plan.exercises.length > 0) {
            const exercisesHeader = document.createElement('h3');
            exercisesHeader.textContent = 'Exercises';
            modalExercises.appendChild(exercisesHeader);

            const exerciseList = document.createElement('ul');
            exerciseList.className = 'exercise-list';

            // Group exercises by day
            const exercisesByDay = {};
            plan.exercises.forEach(exercise => {
                const day = exercise.day || 1;
                if (!exercisesByDay[day]) {
                    exercisesByDay[day] = [];
                }
                exercisesByDay[day].push(exercise);
            });

            // Create sections for each day
            Object.keys(exercisesByDay).sort((a, b) => parseInt(a) - parseInt(b)).forEach(day => {
                const daySection = document.createElement('div');
                daySection.className = 'day-section';

                const dayHeader = document.createElement('h4');
                dayHeader.textContent = `Day ${day}`;
                daySection.appendChild(dayHeader);

                const dayExercises = document.createElement('ul');
                exercisesByDay[day].forEach(exercise => {
                    const exerciseItem = document.createElement('li');
                    exerciseItem.innerHTML = `Exercise ID: ${exercise.id} - ${exercise.sets || 3} sets × ${exercise.reps || 10} reps`;
                    dayExercises.appendChild(exerciseItem);
                });

                daySection.appendChild(dayExercises);
                modalExercises.appendChild(daySection);
            });
        }

        // Show the modal
        modal.style.display = 'block';

        // Close modal when clicking on the X
        document.querySelector('.close-modal').onclick = function () {
            modal.style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }
</script>

<style>
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: var(--color-zinc-900);
        margin: 10% auto;
        padding: 20px;
        border-radius: 12px;
        width: 85%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--color-zinc-800);
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .modal-header h2 {
        margin: 0;
        color: white;
        font-size: 20px;
    }

    .close-modal {
        color: var(--color-zinc-400);
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: white;
    }

    .modal-body {
        color: var(--color-zinc-300);
        font-size: 14px;
        line-height: 1.5;
    }

    .modal-body p {
        margin-bottom: 10px;
    }

    .modal-body strong {
        color: white;
    }

    .modal-exercises {
        margin-top: 20px;
    }

    .modal-exercises h3 {
        color: white;
        font-size: 18px;
        margin-bottom: 10px;
        border-top: 1px solid var(--color-zinc-800);
        padding-top: 15px;
    }

    .modal-exercises h4 {
        color: var(--color-zinc-200);
        font-size: 16px;
        margin: 15px 0 10px 0;
    }

    .modal-exercises ul {
        padding-left: 20px;
        margin: 10px 0;
    }

    .modal-exercises li {
        margin-bottom: 5px;
    }

    /* Custom plan badge */
    .plan-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--color-violet-600);
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Section headers */
    .section-header {
        margin: 5px 0 15px 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 5px 0;
        color: white;
    }

    .section-subtitle {
        color: var(--color-zinc-400);
        font-size: 14px;
        margin: 0;
    }

    /* Custom plans styling */
    .custom-plans {
        margin-bottom: 30px;
    }

    .plan-card.custom-plan {
        border: 1px solid var(--color-violet-800);
        position: relative;
    }
</style>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>