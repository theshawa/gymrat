<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Settings.php";
$settings = new Settings();
try {
    $settings->get_all();
} catch (\Throwable $th) {
    die("Failed to get settings: " . $th->getMessage());
}

require_once "../../db/models/WorkoutSession.php";
$session_model = new WorkoutSession();
$session_model->fill([
    'user' => $_SESSION['auth']['id'],
]);

$sessions = [];
try {
    $sessions = $session_model->get_all_live();
} catch (\Throwable $th) {
    die("Failed to get workout sessions: " . $th->getMessage());
}

$active_sessions = array_filter($sessions, function ($session) use ($settings) {
    return $session->get_duration_in_hours() < $settings->workout_session_expiry;
});

$active_sessions_count = count($active_sessions);
$max_sessions = $settings->max_workout_sessions ?? 60;
$traffic = $active_sessions_count / $max_sessions * 10;
$rat_count_text = "";
if ($active_sessions_count === 0) {
    $rat_count_text = "No rats are working out";
} else if ($active_sessions_count < 5) {
    $rat_count_text = "Less than 5 rats are working out";
} elseif ($active_sessions_count < 10) {
    $traffic_text = "Less than 10 rats are working out";
} else if ($active_sessions_count > $max_sessions) {
    $traffic_text = "More than $max_sessions rats are working out";
} else {
    $traffic_range_min = 0;
    $traffic_range_max = 0;
    for ($i = 10; $i <= $max_sessions; $i += 10) {
        $traffic_range_min = $i;
        $traffic_range_max = $i + 10;
        if ($active_sessions_count >= $traffic_range_min && $active_sessions_count < $traffic_range_max) {
            break;
        }
    }
    $traffic_text = "$traffic_range_min - $traffic_range_max rats are working out";
}


$status_list = [
    "Gym is all yours",
    "Gym is buzzing",
    "Gym is packed",
];

$status = $traffic > 0.66 * 10 ? $status_list[2] : ($traffic > 0.33 * 10 ? $status_list[1] : $status_list[0]);

$pageConfig = [
    "title" => "Live Gym Traffic",
    "styles" => ["./gym-traffic.css"],
    "scripts" => ["./gym-traffic.js"],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Gym Traffic",
        "back_url" => "../",
    ],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<script>
    const $TRAFFIC = <?= number_format($traffic, 2) ?>;
    console.log($TRAFFIC);
</script>

<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'Current',
                'href' => './'
            ],
            [
                'title' => 'Weekly Flow',
                'href' => './week'
            ]
        ],
        "active" => 1
    ];

    require_once "../includes/subnavbar.php";
    ?>
    <div class="meter">
        <div class="arrow"></div>
    </div>
    <span class="label">Current Traffic is <span class="traffic-value">Loading...</span></span>

    <div class="data">
        <h1 class="title"><?= $status ?></h1>
        <div class="active-users">
            <div class="dot"></div>
            <span><?= $rat_count_text ?></span>
        </div>
        <p class="paragraph small">*The traffic values are estimates to give you a general idea and may not reflect exact conditions. Use them as a guide, and remember—you can crush your workout at any time!</p>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>