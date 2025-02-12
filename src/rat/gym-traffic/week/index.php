<?php
$pageConfig = [
    "title" => "Gym Traffic",
    "styles" => ["../gym-traffic.css", "./week.css"],
    "scripts" => [
        "https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js",
        "./week.js"
    ],
    "titlebar" => [
        "title" => "Weekly Gym Traffic",
        "back_url" => "../../",
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

$day = $_GET['day'] ?? 1;
if (!is_numeric($day) || $day < 1 || $day > 7) {
    $day = 1;
}

require_once "./data.php";

$dayData = $data[$day];
$maximumRats = 83;

$traffic_cmp = function ($a, $b) {
    return $a['rats'] - $b['rats'];
};

usort($dayData, $traffic_cmp);

$peakHrs = array_filter($dayData, function ($line) use ($maximumRats) {
    $traffic = $line['rats'] / $maximumRats;
    return $traffic >= 0.66;
});

$peakHrs = array_slice($peakHrs, 0, 3);

$idleHrs = array_filter($dayData, function ($line) use ($maximumRats) {
    $traffic = $line['rats'] / $maximumRats;
    return $traffic >= 0.33 && $traffic < 0.66;
});
$idleHrs = array_slice($idleHrs, 0, 3);

$freeHrs = array_filter($dayData, function ($line) use ($maximumRats) {
    $traffic = $line['rats'] / $maximumRats;
    return $traffic < 0.33;
});
$freeHrs = array_slice($freeHrs, 0, 3);


?>

<script>
    const $DATA = <?= json_encode($dayData) ?>;
    const $MAX_RATS = <?= $maximumRats ?>;
</script>

<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'Live',
                'href' => '../'
            ],
            [
                'title' => 'Weekly Flow',
                'href' => './week'
            ]
        ],
        "active" => 2
    ];

    require_once "../../includes/subnavbar.php";
    ?>
    <div class="day-selector">
        <?php foreach (["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"] as $i => $title): ?>
            <a href="./?day=<?= $i + 1 ?>" class="day-button <?= $day == $i + 1 ? "active" : "" ?>"><?= $title ?></a>
        <?php endforeach; ?>
    </div>

    <canvas id="week-chart"></canvas>

    <div class="hrs">
        <div class="slot free">
            <h4>Free Hours</h4>
            <div class="hrs-list">
                <?php foreach ($freeHrs as $i => $hr): ?>
                    <div class="hr">
                        <p class="time">
                            <?= $hr['from'] > 12 ? ($hr['from'] - 12) . " PM" : $hr['from'] . " AM" ?>
                            <span>-</span>
                            <?= $hr['to'] > 12 ? ($hr['to'] - 12) . " PM" : $hr['to'] . " AM" ?>
                        </p>
                        <span class="traffic">
                            <?= number_format($hr['rats'] / $maximumRats * 10, 1) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="slot idle">
            <h4>Idle Hours</h4>
            <div class="hrs-list">
                <?php foreach ($idleHrs as $i => $hr): ?>
                    <div class="hr">
                        <p class="time">
                            <?= $hr['from'] > 12 ? ($hr['from'] - 12) . " PM" : $hr['from'] . " AM" ?>
                            <span>-</span>
                            <?= $hr['to'] > 12 ? ($hr['to'] - 12) . " PM" : $hr['to'] . " AM" ?>
                        </p>
                        <span class="traffic">
                            <?= number_format($hr['rats'] / $maximumRats * 10, 1) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="slot peak">
            <h4>Peak Hours</h4>
            <div class="hrs-list">
                <?php foreach ($peakHrs as $i => $hr): ?>
                    <div class="hr">
                        <p class="time">
                            <?= $hr['from'] > 12 ? ($hr['from'] - 12) . " PM" : $hr['from'] . " AM" ?>
                            <span>-</span>
                            <?= $hr['to'] > 12 ? ($hr['to'] - 12) . " PM" : $hr['to'] . " AM" ?>
                        </p>
                        <span class="traffic">
                            <?= number_format($hr['rats'] / $maximumRats * 10, 1) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>