<?php

require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$start_command = $_GET['start'] ?? null;

if ($start_command) {
    header("Location: ./start_workout");
    exit;
}

require_once "../../db/models/Customer.php";
$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $user->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

if (!$user->workout) {
    die("You don't have a workout plan. Please contact your trainer to get one.");
}

$workoutSession = null;
if (isset($_SESSION['workout_session'])) {
    require_once "../../db/models/WorkoutSession.php";
    $workoutSession = new WorkoutSession();
    $workoutSession->fill([
        'session_key' => $_SESSION['workout_session']
    ]);
    try {
        $workoutSession->get_by_session_key();

        // automatically end workout if it has been more than 4 hrs
        if ($workoutSession->get_duration_in_hours() > 4) {
            $ended_at = (clone $workoutSession->started_at)->modify('+4 hours');
            $workoutSession->mark_ended($ended_at);
            unset($_SESSION['workout_session']);
            $workoutSession = null;
        }
    } catch (\Throwable $th) {
        die("Failed to get workout session: " . $th->getMessage());
    }
}

require_once "../../db/models/Workout.php";
$workout = new Workout();

try {
    $workout->get_by_id($user->workout);
} catch (\Throwable $th) {
    die("Failed to get workout: " . $th->getMessage());
}

$days = [];

foreach ($workout->exercises as $exercise) {
    $d = $exercise['day'];
    if (in_array($d, $days)) continue;
    $days[] = $d;
}

if (!count($days)) {
    die("No exercises found for your workout. Please contact your trainer.");
}

sort($days);

$day = $_GET['day'] ?? null;
if ($day == null) {
    require_once "../../db/models/WorkoutSession.php";
    $workout_session = new WorkoutSession();
    $workout_session->fill([
        'user' => $user->id,
        'workout' => $workout->id,
    ]);
    try {
        $workout_session->get_last_session();
    } catch (\Throwable $th) {
        die("Failed to get workout session: " . $th->getMessage());
    }

    if ($workout_session->session_key) {
        if ($workout_session->ended_at) {
            // no active session
            $day = $workout_session->day + 1;
            if (!array_search($day, $days)) {
                $day = $days[0];
            }
        } else {
            // active session
            $day = $workout_session->day;
        }
    } else {
        // no session yet
        $day = $days[0];
    }
}

$subnavbar_links = array_map(function ($item) use ($days) {
    return [
        'title' => "Day $item",
        'href' => "./?day=$item"
    ];
}, $days);



$subnavbar_active = $day;

$exercises = [];
require_once "../../db/models/Exercise.php";
$exercise_model = new Exercise();
foreach ($workout->exercises as $exercise_ref) {
    if ($exercise_ref['day'] == $day) {
        try {
            $exercise_model->get_by_id($exercise_ref["exercise_id"]);
            $exercises[] = (object) [
                ...((array) $exercise_model),
                'day' => $exercise_ref['day'],
                'reps' => $exercise_ref['reps'] ?? null,
                'sets' => $exercise_ref['sets'] ?? null,
            ];
        } catch (\Throwable $th) {
            die("Failed to get exercise: " . $th->getMessage());
        }
    }
}

usort($exercises, function ($a, $b) {
    return $a->id - $b->id;
});

$pageConfig = [
    "title" => "My Workout",
    "styles" => ["./workout.css"],
    "scripts" => ["./workout-timer.js"],
    "titlebar" => [
        "title" => "My Workout",
        "back_url" => "/rat/index.php"
    ],
    "navbar_active" => 1
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<script>
    const $WORKOUT_STARTED_AT = <?= json_encode($workoutSession ? $workoutSession->started_at->format("Y-m-d H:i:s") : null) ?>;
</script>

<main>

    <?php if ($workoutSession): ?>
        <button class="btn danger" onclick="endWorkout()">
            <span>End Workout</span>
            <span class="sub-text workout-timer"></span>
        </button>
        <script>
            const endWorkout = () => {
                const startedAt = new Date($WORKOUT_STARTED_AT)
                const now = Date.now();
                const diffInSeconds = Math.floor((now - startedAt) / 1000);
                const seconds = diffInSeconds % 60;
                const minutes = Math.floor(diffInSeconds / 60) % 60;
                const hours = Math.floor(diffInSeconds / 3600) % 24;

                if (hours >= 1 || confirm("Are you sure you want to end the workout? You have been working out for " +
                        (minutes > 0 ? minutes + " minutes" : seconds + " seconds") +
                        ". It's recommended to workout for at least one hour for best results.")) {
                    window.location.href = "./end_workout_process.php";
                }
            }
        </script>
    <?php else: ?>
        <a href="./start_workout" class="btn success">
            <span>Start Workout</span>
        </a>
    <?php endif; ?>

    <?php
    $subnavbarConfig = [
        'links' => $subnavbar_links,
        "active" => $subnavbar_active
    ];

    require_once "../includes/subnavbar.php";
    ?>
    <div class="exercises">
        <?php require_once "../../uploads.php";  ?>
        <?php foreach ($exercises as $exercise): ?>
            <a href="./exercise?id=<?= $exercise->id ?>" class="exercise">
                <img src="<?= $exercise->image ? get_file_url($exercise->image) : get_file_url("default-images/default_exercise.jpg") ?>" alt="Workout image" class="featured-image">
                <div class="right">
                    <h4><?= $exercise->name ?> <span class="count"><?= $exercise->reps ?> x <?= $exercise->sets ?></span></h4>
                    <p class="equipment"><?= $exercise->equipment_needed ?> required</p>
                </div>
                <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.3536 4.35355C10.5488 4.15829 10.5488 3.84171 10.3536 3.64645L7.17157 0.464466C6.97631 0.269204 6.65973 0.269204 6.46447 0.464466C6.2692 0.659728 6.2692 0.976311 6.46447 1.17157L9.29289 4L6.46447 6.82843C6.2692 7.02369 6.2692 7.34027 6.46447 7.53553C6.65973 7.7308 6.97631 7.7308 7.17157 7.53553L10.3536 4.35355ZM0 4.5H10V3.5H0V4.5Z" fill="#71717A" />
                </svg>
            </a>
        <?php endforeach; ?>
    </div>
    </div>
    <div class="info">
        <h4>Your Current Workout is <?= $workout->name ?></h4>
        <p class="paragraph"><?= $workout->description ?></p>
    </div>
</main>


<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>