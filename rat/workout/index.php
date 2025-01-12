<?php
$pageConfig = [
    "title" => "My Workout",
    "styles" => ["./workout.css"],
    "scripts" => ["./workout-timer.js"],
    "titlebar" => [
        "title" => "My Workout",
        "back_url" => "/rat/index.php"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "./data.php";


$start_command = $_GET['start'] ?? null;

if ($start_command) {
    header("Location: ./start_end_workout_process.php");
    exit;
}

$workoutSession = null;
if ($_SESSION['workout_session']) {
    require_once "../../db/models/WorkoutSession.php";
    $workoutSession = new WorkoutSession();
    $workoutSession->fill([
        'id' => $_SESSION['workout_session']
    ]);
    $workoutSession->get_by_id();
}

$day = $_GET['day'] ?? 1;

$subnavbar_links = array_map(function ($day) {
    return [
        'title' => $day['title'],
        'href' => "./?day=$day[id]"
    ];
}, $workout);

$subnavbar_active = array_search($day, array_column($workout, 'id'));
$subnavbar_active = $subnavbar_active === false ? 1 : $subnavbar_active + 1;

$day = array_filter($workout, fn($d) => $d['id'] == $day);
if (count($day) === 0) {
    $day = $workout[0]['exercises'];
} else {
    $day = array_values($day)[0]['exercises'];
}

?>

<script>
    const $WORKOUT_STARTED_AT = <?= json_encode($workoutSession ? $workoutSession->started_at->format("Y-m-d H:i:s") : null) ?>;
</script>

<main>
    <a href="./start_end_workout_process.php" class="btn <?= $workoutSession ? "danger" : "success" ?>">
        <span><?= $workoutSession ? "End" : "Start" ?> Workout</span>
        <?php if ($workoutSession): ?>
            <span class="sub-text workout-timer"></span>
        <?php endif; ?>
    </a>

    <?php
    $subnavbarConfig = [
        'links' => $subnavbar_links,
        "active" => $subnavbar_active
    ];

    require_once "../includes/subnavbar.php";
    ?>
    <?php foreach ($day as $d): ?>
        <div class="category">
            <button class="category-title">
                <h3><?= $d['name'] ?></h3>
                <div class="right">
                    <span class="time"><?= $d['time'] ?> mins</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="cavet">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.64645 5.14645C7.84171 4.95118 8.15829 4.95118 8.35355 5.14645L13.3536 10.1464C13.5488 10.3417 13.5488 10.6583 13.3536 10.8536C13.1583 11.0488 12.8417 11.0488 12.6464 10.8536L8 6.20711L3.35355 10.8536C3.15829 11.0488 2.84171 11.0488 2.64645 10.8536C2.45118 10.6583 2.45118 10.3417 2.64645 10.1464L7.64645 5.14645Z" fill="#71717A" />
                    </svg>
                </div>
            </button>
            <div class="exercises-wrapper">
                <div class="exercises">
                    <?php foreach ($d['items'] as $exercise): ?>
                        <a href="./exercise?id=" class="exercise">
                            <img src="<?= $exercise['image'] ?>" alt="Workout image" class="featured-image">
                            <div class="right">
                                <h4><?= $exercise['name'] ?></h4>
                                <div class="bottom">
                                    <?php if ($exercise['reps']): ?>
                                        <span><?= $exercise['reps'] ?> reps</span>
                                    <?php endif; ?>
                                    <?php if ($exercise['time']): ?>
                                        <span><?= $exercise['time'] ?> mins</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.3536 4.35355C10.5488 4.15829 10.5488 3.84171 10.3536 3.64645L7.17157 0.464466C6.97631 0.269204 6.65973 0.269204 6.46447 0.464466C6.2692 0.659728 6.2692 0.976311 6.46447 1.17157L9.29289 4L6.46447 6.82843C6.2692 7.02369 6.2692 7.34027 6.46447 7.53553C6.65973 7.7308 6.97631 7.7308 7.17157 7.53553L10.3536 4.35355ZM0 4.5H10V3.5H0V4.5Z" fill="#71717A" />
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</main>

<script>
    document.querySelectorAll(".category").forEach((category) => {
        const toggler = category.querySelector("button");

        toggler.addEventListener("click", () => {
            category.classList.toggle("hidden");
        });
    });
</script>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>