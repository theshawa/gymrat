<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

// $range = (int)htmlspecialchars($_GET['range'] ?? "30");

$records = [];

require_once "../../db/models/BmiRecord.php";

$record = new BmiRecord();

try {
    $records = $record->get_all_of_user($_SESSION['auth']['id']);
} catch (PDOException $e) {
    die("Failed to get records due to error: " . $e->getMessage());
}



$range = 90;

$records = array_values(array_filter($records, function (BmiRecord $item) use ($range) {
    $now = new DateTime();
    $diff = $now->diff($item->created_at);
    return $diff->days <= ($range < 0 ? 99999 : $range);
}));

// get insights

if (count($records) > 1) {

    $oldest_record = $records[count($records) - 1];
    $latest_record = $records[0];

    $weight_increase = $latest_record->weight - $oldest_record->weight;
    $bmi_increase = $latest_record->bmi - $oldest_record->bmi;
    $weight_increase_percentage = ($weight_increase / $oldest_record->weight) * 100;
    $bmi_increase_percentage = ($bmi_increase / $oldest_record->bmi) * 100;

    $category_counts = [
        'underweight' => 0,
        'normal' => 0,
        'overweight' => 0,
        'obese' => 0
    ];
    foreach ($records as $record) {
        if ($record->bmi < 18.5) {
            $category_counts['underweight']++;
        } elseif ($record->bmi < 24.9) {
            $category_counts['normal']++;
        } elseif ($record->bmi < 29.9) {
            $category_counts['overweight']++;
        } else {
            $category_counts['obese']++;
        }
    }
}


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
    "navbar_active" => 1
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
                'title' => 'Trainer Logs',
                'href' => './logs'
            ],
            [
                'title' => 'BMI Progress',
                'href' => './'
            ],
        ],
        "active" => 2
    ];

    require_once "../includes/subnavbar.php"; ?>
    <h1>BMI Progress Chart</h1>
    <p class="info">
        View your BMI history in the chart below. <strong>For a healthy weight, keep your BMI between the green lines</strong>. This will help you track your fitness progress over time.
    </p>

    <?php if (count($records) === 0): ?>
        <p class="no-records">Hey there! Looks like you haven't recorded your BMI yet.
            Let's start tracking your fitness journey together - add your first measurement!
        </p>
    <?php else: ?>
        <!-- <form class="filter" action=".">
            <select class="input" name="range" required>
                <?php foreach ($rangeOptions as $option): ?>
                    <option value="<?= $option['value'] ?>" <?= $option['value'] == $range ? 'selected' : '' ?>>
                        <?= $option['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form> -->
        <canvas id="progress-chart"></canvas>
        <?php if (count($records) > 1): ?>

            <div class="insights">
                <h3>Insights</h3>
                <div class="insight-list">
                    <div class="insight">
                        <span class="title">Weight Change</span>
                        <p class="value"><?= number_format($weight_increase, 2) ?> kg (<?= number_format($weight_increase_percentage, 2) ?>%)</p>
                    </div>
                    <div class="insight">
                        <span class="title">BMI Change</span>
                        <p class="value"><?= number_format($bmi_increase, 2) ?> (<?= number_format($bmi_increase_percentage, 2) ?>%)</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <form action="delete_all_bmi_records.php" class="delete_form" method="post" style="margin-top: 20px;width: 100%;display: flex;flex-direction: column;">
        <a href="../bmi" class="btn" style="margin-bottom: 10px;">Add new record</a>
        <?php if (count($records) > 0): ?>
            <button class="btn secondary">Clear Current records</button>
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