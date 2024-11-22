<?php
$pageConfig = [
    "title" => "My Progress",
    "styles" => ["../progress.css"],
    "titlebar" => [
        "back_url" => "../../"
    ],
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

$records = [
    [
        'id' => 1,
        'time' => '2023-11-11 14:23:00',
        'message' => "You're improving your strength steadily.",
        'status' => 'good'
    ],
    [
        'id' => 2,
        'time' => '2023-11-12 09:15:00',
        'message' => "Keep up the good work, but try to push a bit harder.",
        'status' => 'bad'
    ],
    [
        'id' => 3,
        'time' => '2023-11-13 16:45:00',
        'message' => "Excellent progress on your endurance.",
        'status' => 'good'
    ],
    [
        'id' => 4,
        'time' => '2023-11-14 11:30:00',
        'message' => "You need to focus more on your diet.",
        'status' => 'bad'
    ],
    [
        'id' => 5,
        'time' => '2023-11-15 08:00:00',
        'message' => "Great job on your flexibility exercises.",
        'status' => 'good'
    ],
    [
        'id' => 6,
        'time' => '2023-11-16 10:30:00',
        'message' => "You missed your workout today.",
        'status' => 'bad'
    ],
    [
        'id' => 7,
        'time' => '2023-11-17 12:45:00',
        'message' => "Your stamina is improving.",
        'status' => 'good'
    ],
    [
        'id' => 8,
        'time' => '2023-11-18 14:20:00',
        'message' => "Try to maintain a consistent workout schedule.",
        'status' => 'bad'
    ],
    [
        'id' => 9,
        'time' => '2023-11-19 09:10:00',
        'message' => "Excellent performance in today's session.",
        'status' => 'good'
    ],
    [
        'id' => 10,
        'time' => '2023-11-20 11:50:00',
        'message' => "You need to work on your balance.",
        'status' => 'bad'
    ],
    [
        'id' => 11,
        'time' => '2023-11-21 13:30:00',
        'message' => "Great improvement in your strength training.",
        'status' => 'good'
    ],
    [
        'id' => 12,
        'time' => '2023-11-22 15:00:00',
        'message' => "You skipped your warm-up exercises.",
        'status' => 'bad'
    ],
    [
        'id' => 13,
        'time' => '2023-11-23 16:40:00',
        'message' => "Your endurance is getting better.",
        'status' => 'good'
    ],
    [
        'id' => 14,
        'time' => '2023-11-24 18:20:00',
        'message' => "Focus more on your core exercises.",
        'status' => 'bad'
    ]
];

?>

<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'BMI',
                'href' => '../'
            ],
            [
                'title' => 'Trainer Logs',
                'href' => './'
            ]
        ],
        'active' => 2
    ];

    require_once "../../includes/subnavbar.php"; ?>
    <div class="log-record-list">
        <?php foreach ($records as $record) : ?>
            <div href="view.php?id=<?= $record['id'] ?>" class="log-record">
                <p class="message"><?= $record['message'] ?></p>
                <div class="bottom">
                    <span class="time"><?= date_create($record['time'])->format('M d, Y') ?></span>
                    <span class="status <?= $record['status'] ?>"><?= ['good' => 'Well Done', 'bad' => 'Try Harder'][$record['status']] ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>