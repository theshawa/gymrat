<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../../db/models/Customer.php";
$user  = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id']
]);

require_once "../../../alerts/functions.php";
try {
    $user->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get user: " . $th->getMessage());
}

if (!$user->trainer) {
    redirect_with_error_alert("You don't have a trainer. Please contact support to get one.", "../");
    exit;
}

require_once "../../../db/models/TrainerLogRecord.php";
$logRecord = new TrainerLogRecord();
$records = [];
try {
    $records = $logRecord->get_all_of_user_with_trainer($user->id, $user->trainer);
} catch (\Throwable $th) {
    die("Failed to get log records: " . $th->getMessage());
}

$good_count = 0;
$bad_count = 0;
foreach ($records as $record) {
    if ($record->performance_type == 'well_done') {
        $good_count++;
    } else if ($record->performance_type == 'try_harder') {
        $bad_count++;
    }
}

$pageConfig = [
    "title" => "My Progress",
    "styles" => ["../progress.css"],
    "titlebar" => [
        "back_url" => "../../"
    ],
    "navbar_active" => 1
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>

<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'Trainer Logs',
                'href' => './'
            ],
            [
                'title' => 'BMI Progress',
                'href' => '../'
            ]
        ],
        'active' => 1
    ];

    require_once "../../includes/subnavbar.php"; ?>
    <h1>Trainer Feedback</h1>
    <p class="info">See your trainer’s feedback and track your progress. Review highlights, achievements, and tips to help you improve and stay motivated.</p>
    <?php if (!empty($records)): ?>
        <?php if ($good_count > floor(count($records) / 2)): ?>
            <div class="feedback good">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smile-icon lucide-smile">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" x2="9.01" y1="9" y2="9" />
                        <line x1="15" x2="15.01" y1="9" y2="9" />
                    </svg>
                    <span>Well Done!</span>
                </h3>
                <p class="paragraph">You have received <?= $good_count ?> positive feedback<?= $good_count === 1 ? '' : 's' ?> from your trainer. Keep up the great work!</p>
            </div>
        <?php else: ?>
            <div class="feedback bad">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-biceps-flexed-icon lucide-biceps-flexed">
                        <path d="M12.409 13.017A5 5 0 0 1 22 15c0 3.866-4 7-9 7-4.077 0-8.153-.82-10.371-2.462-.426-.316-.631-.832-.62-1.362C2.118 12.723 2.627 2 10 2a3 3 0 0 1 3 3 2 2 0 0 1-2 2c-1.105 0-1.64-.444-2-1" />
                        <path d="M15 14a5 5 0 0 0-7.584 2" />
                        <path d="M9.964 6.825C8.019 7.977 9.5 13 8 15" />
                    </svg>
                    <span>Try Harder!</span>
                </h3>
                <p class="paragraph">You have received <?= $bad_count ?> feedback<?= $bad_count === 1 ? '' : 's' ?> from your trainer. Don't worry, you can improve!</p>
            </div>
        <?php endif; ?>
        <div class="log-record-list">
            <?php foreach ($records as $record) : ?>
                <div class="log-record">
                    <p class="message"><?= $record->message ?></p>
                    <div class="bottom">
                        <span class="time"><?php
                                            require_once "../../../utils.php";
                                            echo format_time($record->created_at); ?></span>
                        <span class="status <?= $record->performance_type ?>"><?= ['well_done' => 'Well Done', 'try_harder' => 'Try Harder'][$record->performance_type] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">You don't have any trainer feedback yet. Keep working out and your trainer will provide feedback soon!</p>
    <?php endif; ?>


</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>