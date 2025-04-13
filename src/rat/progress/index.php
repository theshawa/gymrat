<?php
session_start();

$range = (int)htmlspecialchars($_GET['range'] ?? "30");

$records = [];

require_once "../../db/models/BmiRecord.php";
require_once "../../alerts/functions.php";

$record = new BmiRecord();

try {
    $records = $record->get_all_of_user($_SESSION['auth']['id']);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get records due to error: " . $e->getMessage(), "./");
}

$records = array_filter($records, function (BmiRecord $item) use ($range) {
    $now = new DateTime();
    $diff = $now->diff($item->created_at);
    return $diff->days <= ($range < 0 ? 99999 : $range);
});

$records_as_arrays = array_map(function (BmiRecord $item) {
    return (array) $item;
}, $records);

$labels = array_column($records_as_arrays, 'created_at');
$values = array_column($records_as_arrays, 'bmi');

usort($records_as_arrays, function ($a, $b) {
    $at = $a['created_at'];
    $bt = $b['created_at'];
    if ($at == $bt) return 0;
    return $at < $bt ? 1 : -1;
});

$rangeOptions = [
    [
        'title' => 'Records of past month',
        'value' => 30
    ],
    [
        'title' => 'Records of past 2 months',
        'value' => 60
    ],
    [
        'title' => 'Records of past 6 months',
        'value' => 180
    ],
    [
        'title' => 'Records of past year',
        'value' => 365
    ],
    [
        'title' => 'Records from the start',
        'value' => -1
    ]
];

$pageConfig = [
    "title" => "My Progress",
    "titlebar" => [
        "back_url" => "../"
    ],
    "styles" => ["./progress.css"],
    "scripts" => [
        "https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js",
        "https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.1.0/dist/chartjs-plugin-annotation.min.js",
        "./bmi-progress.js"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<script>
    const $LABELS = <?= json_encode($labels) ?>;
    const $VALUES = <?= json_encode($values) ?>;
</script>
<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'BMI',
                'href' => './'
            ],
            [
                'title' => 'Trainer Logs',
                'href' => './logs'
            ]
        ],
        "active" => 1
    ];

    require_once "../includes/subnavbar.php"; ?>
    <form class="filter" action=".">
        <select class="input" name="range" required>
            <?php foreach ($rangeOptions as $option): ?>
                <option value="<?= $option['value'] ?>" <?= $option['value'] == $range ? 'selected' : '' ?>>
                    <?= $option['title'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php if (count($records) === 0): ?>
        <p class="info">No records found</p>
    <?php else: ?>
        <canvas id="progress-chart"></canvas>
        <p class="info">
            *Your BMI progress is displayed in the chart above. To maintain a healthy BMI, ensure that your values fall between the two green lines.
        </p>
    <?php endif; ?>


    <form action="delete_all_bmi_records.php" class="delete_form" method="post" style="margin-top: 40px;width: 100%;display: flex;flex-direction: column;">
        <a href="../bmi" class="btn" style="margin-bottom: 20px;">Add new record</a>
        <?php if (count($records) > 0): ?>
            <button class="btn outlined">Clear Current records</button>
        <?php endif; ?>
    </form>
</main>

<script>
    const deleteForm = document.querySelector('.delete_form');
    deleteForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (confirm('Are you sure you want to delete all BMI records?')) {
            deleteForm.submit();
        }
    });
</script>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>