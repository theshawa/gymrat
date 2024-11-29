<?php
$pageConfig = [
    "title" => "Notifications",
    "styles" => ["./notifications.css"],
    "navbar_active" => 2,
    "titlebar" => [
        "title" => "Notifications(4)",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

$notifications = [
    [
        'id' => 1,
        'title' => 'Temporary Gym Closure',
        'description' => 'The gym will be temporarily closed for maintenance. We apologize for the inconvenience.',
        'time' => new DateTime('2021-09-09 12:08:00')
    ],
    [
        'id' => 2,
        'title' => 'New Yoga Classes',
        'description' => 'We are excited to announce new yoga classes starting next week. Join us for a relaxing session.',
        'time' => new DateTime('2021-09-10 09:00:00')
    ],
    [
        'id' => 3,
        'title' => 'Pool Maintenance',
        'description' => 'The pool will be closed for maintenance. We appreciate your understanding.',
        'time' => new DateTime('2021-09-11 14:30:00')
    ],
    [
        'id' => 4,
        'title' => 'Holiday Hours',
        'description' => 'Please note our holiday hours for the upcoming season. Happy holidays!',
        'time' => new DateTime('2021-09-12 08:00:00')
    ],
    [
        'id' => 5,
        'title' => 'New Equipment Arrival',
        'description' => 'We are thrilled to announce the arrival of new gym equipment. Come check it out!',
        'time' => new DateTime('2021-09-13 10:00:00')
    ],
    [
        'id' => 6,
        'title' => 'Guest Speaker Event',
        'description' => 'Join us for an inspiring talk by our guest speaker. Don’t miss out!',
        'time' => new DateTime('2021-09-14 16:00:00')
    ]
]

?>

<main>
    <?php foreach ($notifications as $notification): ?>
        <a href="/rat/notifications/notification.php?id=<?= $notification['id']  ?>" class="notification">
            <h4><?= $notification['title']  ?></h4>
            <p class="paragraph truncate"><?= $notification['description']  ?></p>
            <div class="line">
                <span><?= $notification['time']->format("F j, Y, g:i a")  ?></span>
                <span>Read More</span>
            </div>
        </a>
    <?php endforeach; ?>

</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>