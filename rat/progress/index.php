<?php
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
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

$range = (int)htmlspecialchars($_GET['range'] ?? "30");

$records = [
    [
        'time' => '2023-11-11 14:23:00',
        'bmi' => 35.0,
        'id' => 1
    ],
    [
        'time' => '2023-12-11 09:45:00',
        'bmi' => 34.0,
        'id' => 2
    ],
    [
        'time' => '2024-01-11 16:30:00',
        'bmi' => 33.0,
        'id' => 3
    ],
    [
        'time' => '2024-02-11 11:15:00',
        'bmi' => 32.0,
        'id' => 4
    ],
    [
        'time' => '2024-03-11 08:50:00',
        'bmi' => 31.0,
        'id' => 5
    ],
    [
        'time' => '2024-04-11 19:05:00',
        'bmi' => 30.0,
        'id' => 6
    ],
    [
        'time' => '2024-05-11 13:20:00',
        'bmi' => 29.0,
        'id' => 7
    ],
    [
        'time' => '2024-06-11 07:55:00',
        'bmi' => 28.0,
        'id' => 8
    ],
    [
        'time' => '2024-07-11 21:40:00',
        'bmi' => 27.0,
        'id' => 9
    ],
    [
        'time' => '2024-08-11 10:25:00',
        'bmi' => 26.0,
        'id' => 10
    ],
    [
        'time' => '2024-09-11 18:10:00',
        'bmi' => 25.0,
        'id' => 11
    ],
    [
        'time' => '2024-10-11 12:35:00',
        'bmi' => 24.0,
        'id' => 12
    ],
    [
        'time' => '2024-11-11 15:50:00',
        'bmi' => 23.0,
        'id' => 13
    ],
    [
        'time' => '2024-11-16 09:30:00',
        'bmi' => 24.0,
        'id' => 14
    ],
    [
        'time' => '2024-11-18 17:45:00',
        'bmi' => 22.0,
        'id' => 15
    ],
    [
        'time' => '2024-11-20 20:15:00',
        'bmi' => 21.0,
        'id' => 16
    ],
    [
        'time' => '2024-11-21 06:05:00',
        'bmi' => 25.0,
        'id' => 17
    ],
];

$records = array_filter($records, function ($item) use ($range) {
    $time = new DateTime($item['time']);
    $now = new DateTime();
    $diff = $now->diff($time);
    return $diff->days <= ($range < 0 ? 99999 : $range);
});

$labels = array_column($records, 'time');
$values = array_column($records, 'bmi');

usort($records, function ($a, $b) {
    $at = new DateTime($a['time']);
    $bt = new DateTime($b['time']);
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
]

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
        ]
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
    <canvas id="progress-chart"></canvas>
    <p class="info">
        *Your BMI progress is displayed in the chart above. To maintain a healthy BMI, ensure that your values fall between the two green lines.
    </p>
    <div class="list">
        <?php foreach ($records as $record): ?>
            <div class="item">
                <span class="time"><?= date_format(date_create($record['time']), 'M d, Y, h:i A') ?></span>
                <span class="bmi <?= $record['bmi'] > 24.9 ? "danger" : ($record['bmi'] > 18.5 ? "normal" : "danger") ?>"><?= number_format($record['bmi'], 2) ?></span>
                <form action="./delete_bmi_record.php" method="post">
                    <button title="Delete this record" type="submit" name="id" value="<?= $record['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </form>
            </div>
        <?php endforeach ?>
    </div>
    <a href="../bmi" class="btn">Add new record</a>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>