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
            <div class="log-record">
                <p class="message"><?= $record->message ?></p>
                <div class="bottom">
                    <?php


                    ?>
                    <span class="time"><?php
                                        require_once "../../../utils.php";
                                        echo format_time($record->created_at); ?></span>
                    <span class="status <?= $record->performance_type ?>"><?= ['well_done' => 'Well Done', 'try_harder' => 'Try Harder'][$record->performance_type] ?></span>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>