<?php

require_once "../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$workoutSession =  null;

require_once "../db/models/Settings.php";
$settings = new Settings();
try {
    $settings->get_all();
} catch (\Throwable $th) {
    die("Failed to get settings: " . $th->getMessage());
}

if (isset($_SESSION['workout_session'])) {
    require_once "../db/models/WorkoutSession.php";
    $workoutSession = new WorkoutSession();
    $workoutSession->fill([
        'session_key' => $_SESSION['workout_session']
    ]);

    $hrs = empty($settings->workout_session_expiry)  ? 4 : $settings->workout_session_expiry;
    try {
        $workoutSession->get_by_session_key();

        // automatically end workout if it has been more than 4 hrs
        if ($workoutSession->get_duration_in_hours() > $hrs) {
            $ended_at = (clone $workoutSession->started_at)->modify('+4 hours');
            $workoutSession->mark_ended($ended_at);
            unset($_SESSION['workout_session']);
            $workoutSession = null;
        }
    } catch (\Throwable $th) {
        die("Failed to get workout session: " . $th->getMessage());
    }
}


require_once "../db/models/Customer.php";

$customer = new Customer();
$customer->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $customer->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}


require_once "../db/models/WorkoutSession.php";
$lastSession = new WorkoutSession();
$lastSession->fill([
    'user' => $_SESSION['auth']['id'],
    'workout' => $customer->workout,
]);
$lastSessionText = "It's your first time!";
try {
    $lastSession->get_last_session();
} catch (\Throwable $th) {
    die("Failed to get workout session: " . $th->getMessage());
}
require_once "../utils.php";
if ($lastSession->day) {
    $lastSessionText = "Last workout<br/>" . format_time($lastSession->started_at, true);
}

require_once "../db/models/MembershipPlan.php";
$plan = new MembershipPlan();
$plan->fill([
    'id' => $customer->membership_plan
]);
try {
    $plan->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get membership plan: " . $th->getMessage());
}

require_once "./gym-traffic/functions.php";
$traffic = get_traffic();

$meal_text = "";
if ($customer->meal_plan) {

    require_once "../db/models/MealPlan.php";
    $mealPlan = new MealPlan();
    try {
        $mealPlan->get_by_id($customer->meal_plan);
    } catch (\Throwable $th) {
        die("Failed to get meal plan: " . $th->getMessage());
    }
    $today_weekday_index = date('w');
    $todays_meals =  [];
    require_once "../db/models/Meal.php";
    foreach ($mealPlan->meals as $mealRef) {
        $meal  = new Meal();
        try {
            $meal->get_by_id($mealRef['meal_id']);
        } catch (\Throwable $th) {
            die("Failed to get meal: " . $th->getMessage());
        }
        $timestamp = strtotime($mealRef['day']);
        $mealRefWeekDayIndex = date('N', $timestamp);
        if ($mealRefWeekDayIndex === $today_weekday_index) {
            $todays_meals[] = $meal;
        }
    }

    if (empty($todays_meals)) {
        $meal_text = "No meals today";
    } else if (count($todays_meals) === 1) {
        $meal_text = "Today's meal is " . $todays_meals[0]->name;
    } else {
        $meal_text = count($todays_meals) . " meals today";
    }
}

require_once "../uploads.php";

$trainer_data = [];
if ($customer->trainer) {
    require_once "../db/models/Trainer.php";
    $trainer = new Trainer();
    $trainer->fill([
        'id' => $customer->trainer
    ]);
    try {
        $trainer->get_by_id();
    } catch (\Throwable $th) {
        die("Failed to get trainer: " . $th->getMessage());
    }
    $trainer_data['name'] = $trainer->fname . " " . $trainer->lname;
    $trainer_data['avatar'] = get_file_url($trainer->avatar, "default-images/default-avatar.png");
}

$progress = [];
if ($customer->trainer) {
    require_once "../db/models/TrainerLogRecord.php";
    $log_record_model = new TrainerLogRecord();
    try {
        $log_records = $log_record_model->get_all_of_user(
            $customer->id,
        );
    } catch (\Throwable $th) {
        die("Failed to get trainer log records: " . $th->getMessage());
    }
    if (!empty($log_records)) {
        $progress['status'] = $log_records[0]->performance_type;
        if (strlen($log_records[0]->message) > 40) {
            $progress['text'] = $progress['status'] === "well_done" ? "Well done!" : "Try harder!";
            $progress['text'] = "Trainer says<br/><q>" . $progress['text'] . "</q>";
        } else {
            $progress['text'] = "Trainer says<br/><q>" . $log_records[0]->message . "</q>";
        }
    } else {
        $progress['status'] = "well_done";
        $progress['text'] = "Trainer will give you a feedback soon!";
    }
}

$bmi_text = "";
require_once "../db/models/BmiRecord.php";
$bmi_record = new BmiRecord();
$bmi_record->fill([
    'user' => $_SESSION['auth']['id']
]);
try {
    $bmi_record->get_last_of_user();
} catch (\Throwable $th) {
    die("Failed to get bmi record: " . $th->getMessage());
}

require_once "./bmi/functions.php";

if ($bmi_record->bmi > 0) {
    $bmi_classification = get_bmi_classification($bmi_record->bmi);
    $bmi_text = "Last saved BMI is " . number_format($bmi_record->bmi, 1) . ".<br/>" . ($bmi_classification['bad'] ? "Need attention!" : "Looking good!");
} else {
    $bmi_text = "Try calculating<br/>your BMI";
}


$pageConfig = [
    "title" => "Home",
    "styles" => [
        "./home.css"
    ],
    "scripts" => [
        "./workout/workout-timer.js"
    ],
    "navbar_active" => 1,
];

$fname = $_SESSION['auth']['fname'];
$pageConfig['titlebar']['title'] = "Hi, $fname!";

require_once "./includes/header.php";

?>

<script>
    const $WORKOUT_STARTED_AT = <?= json_encode($workoutSession ? $workoutSession->started_at->format("Y-m-d H:i:s") : null) ?>;
</script>

<?php
$now = new DateTime();
$expire_date =  $customer->membership_plan_activated_at;
$expire_date->modify('+30 days');

$interval = $now->diff($expire_date);
$plan_remaining_days = $interval->days;

?>


<main class="no-padding">
    <img class="banner-image" src="<?= get_file_url($settings->gym_banner, "default-images/default-gym-banner.webp") ?>" />
    <div class="grid">
        <div class="banner-content">
            <h1>Hello <?= $fname ?></h1>
            <p class="paragraph">It's <?= date('l') ?>, <?= date('H') < 12 ? 'Good morning' : (date('H') < 17 ? 'Good afternoon' : 'Good evening') ?>!</p>
        </div>
        <?php if ($customer->workout): ?>
            <a href="/rat/workout" class="grid-tile <?= $workoutSession ? "workout-in-progress" : "" ?>">
                <div class="top">
                    <h2>Workout</h2>
                    <?php if (!$workoutSession): ?>
                        <p><?= $lastSessionText ?></p>
                    <?php else: ?>
                        <p>In progress</p>
                    <?php endif; ?>
                </div>
                <?php if ($workoutSession): ?>
                    <p class="bottom-text sub-text workout-timer"></p>
                <?php else: ?>
                    <button id="start-workout-btn" class="workout-cmd">
                        <span>Start</span>
                        <svg width="24" height="24" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.125 4.0625C3.125 3.545 3.545 3.125 4.0625 3.125H7.8125C8.33 3.125 8.75 3.545 8.75 4.0625V7.8125C8.75 8.33 8.33 8.75 7.8125 8.75H4.0625C3.81386 8.75 3.5754 8.65123 3.39959 8.47541C3.22377 8.2996 3.125 8.06114 3.125 7.8125V4.0625ZM3.125 12.1875C3.125 11.67 3.545 11.25 4.0625 11.25H7.8125C8.33 11.25 8.75 11.67 8.75 12.1875V15.9375C8.75 16.455 8.33 16.875 7.8125 16.875H4.0625C3.81386 16.875 3.5754 16.7762 3.39959 16.6004C3.22377 16.4246 3.125 16.1861 3.125 15.9375V12.1875ZM11.25 4.0625C11.25 3.545 11.67 3.125 12.1875 3.125H15.9375C16.455 3.125 16.875 3.545 16.875 4.0625V7.8125C16.875 8.33 16.455 8.75 15.9375 8.75H12.1875C11.9389 8.75 11.7004 8.65123 11.5246 8.47541C11.3488 8.2996 11.25 8.06114 11.25 7.8125V4.0625Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.625 5.625H6.25V6.25H5.625V5.625ZM5.625 13.75H6.25V14.375H5.625V13.75ZM13.75 5.625H14.375V6.25H13.75V5.625ZM11.25 11.25H11.875V11.875H11.25V11.25ZM11.25 16.25H11.875V16.875H11.25V16.25ZM16.25 11.25H16.875V11.875H16.25V11.25ZM16.25 16.25H16.875V16.875H16.25V16.25ZM13.75 13.75H14.375V14.375H13.75V13.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <script>
                        document.getElementById("start-workout-btn").addEventListener("click", (e) => {
                            e.stopPropagation();
                            e.preventDefault();
                            window.location.href = '/rat/workout?start=true';
                        });
                    </script>
                <?php endif; ?>
            </a>
        <?php else: ?>
            <div class="grid-tile grayed">
                <div class="top">
                    <h2>Workout</h2>
                    <p>Not Assigned</p>
                </div>
                <p class="bottom-text">Your personal workout program is coming soon!</p>
            </div>
        <?php endif; ?>

        <?php if ($customer->meal_plan): ?>
            <a href="/rat/meal-plan" class="grid-tile">
                <div class="top">
                    <h2>Meal Plan</h2>
                </div>
                <p class="bottom-text"><?= $meal_text ?></p>
            </a>
        <?php else: ?>
            <div class="grid-tile grayed">
                <div class="top">
                    <h2>Meal Plan</h2>
                    <p>Not Assigned</p>
                </div>
                <p class="bottom-text">A personal meal plan is coming your way!</p>
            </div>
        <?php endif; ?>
        <a href="/rat/gym-traffic" class="grid-tile traffic-tile">
            <div class="top">
                <h2>Gym Traffic</h2>
            </div>
            <div class="traffic <?= $traffic['status'] ?>">
                <?php if ($traffic['value'] * 100 > 5): ?>
                    <div class="bar">
                        <div class="fill" style="height: <?= 100 * $traffic['value'] ?>%;"></div>
                    </div>
                <?php else: ?>
                    <svg width="30" height="30" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.8333 23.3333H2.33334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.8333 5.32236V24.1722C12.8334 24.3494 12.8738 24.5243 12.9515 24.6835C13.0293 24.8428 13.1422 24.9823 13.2819 25.0913C13.4216 25.2004 13.5842 25.2762 13.7576 25.3131C13.9309 25.3499 14.1104 25.3467 14.2823 25.3039L22.1667 23.3334V6.48903C22.1666 5.9687 21.9926 5.46334 21.6724 5.05325C21.3521 4.64315 20.904 4.35187 20.3992 4.22569L15.7325 3.05903C15.3886 2.97307 15.0297 2.96659 14.6829 3.04007C14.3362 3.11356 14.0107 3.26508 13.7313 3.48313C13.4518 3.70118 13.2257 3.98003 13.0701 4.29851C12.9146 4.61699 12.8336 4.9679 12.8333 5.32236Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.8333 4.66669H9.33333C8.71449 4.66669 8.121 4.91252 7.68342 5.3501C7.24583 5.78769 7 6.38118 7 7.00002V23.3334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16.3333 14H16.345" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M25.6667 23.3333H22.1667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
                <span><?= $traffic['status_text'] ?></span>
            </div>
        </a>
        <a href="<?= $customer->trainer ? "/rat/progress/logs" : "/rat/progress" ?>" class="grid-tile">
            <div class="top">
                <h2>My Progress</h2>
            </div>
            <?php if ($customer->trainer): ?>
                <div class="progress <?= $progress['status'] ?>">
                    <?php if ($progress['status'] === "well_done"): ?>
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.4772 15.1865C15.009 13.9538 15.9497 12.9423 17.1406 12.3226C18.3315 11.7028 19.6997 11.5129 21.0144 11.7847C22.3291 12.0565 23.5099 12.7735 24.3574 13.8147C25.2049 14.8559 25.6673 16.1575 25.6667 17.5C25.6667 22.0103 21 25.6667 15.1667 25.6667C10.4102 25.6667 5.65484 24.71 3.06718 22.7943C2.57018 22.4257 2.33101 21.8237 2.34384 21.2053C2.47101 14.8435 3.06484 2.33334 11.6667 2.33334C12.5949 2.33334 13.4852 2.70209 14.1416 3.35847C14.7979 4.01485 15.1667 4.90509 15.1667 5.83334C15.1667 6.45218 14.9208 7.04567 14.4833 7.48326C14.0457 7.92084 13.4522 8.16668 12.8333 8.16668C11.5442 8.16668 10.92 7.64868 10.5 7.00001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17.5 16.3333C16.7959 15.805 15.9814 15.4428 15.1174 15.274C14.2534 15.1052 13.3625 15.1341 12.5113 15.3586C11.6601 15.5831 10.8707 15.9973 10.2024 16.5702C9.53401 17.1431 9.00399 17.8598 8.65201 18.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.6247 7.96249C9.35551 9.30649 11.0833 15.1667 9.33334 17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    <?php else: ?>
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.8 16.8L11.2 11.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M21.7665 25.0658C21.329 25.5035 20.7355 25.7494 20.1167 25.7496C19.4978 25.7497 18.9043 25.5039 18.4666 25.0664C18.0289 24.6289 17.783 24.0354 17.7829 23.4166C17.7827 22.7977 18.0285 22.2042 18.466 21.7665L16.4045 23.8292C15.9668 24.2668 15.3732 24.5127 14.7542 24.5127C14.1353 24.5127 13.5417 24.2668 13.104 23.8292C12.6663 23.3915 12.4204 22.7979 12.4204 22.1789C12.4204 21.5599 12.6663 20.9663 13.104 20.5287L20.5287 13.104C20.9663 12.6663 21.5599 12.4204 22.1789 12.4204C22.7979 12.4204 23.3915 12.6663 23.8292 13.104C24.2668 13.5417 24.5127 14.1353 24.5127 14.7542C24.5127 15.3732 24.2668 15.9668 23.8292 16.4045L21.7665 18.466C22.2042 18.0285 22.7977 17.7827 23.4166 17.7829C24.0354 17.783 24.6289 18.0289 25.0664 18.4666C25.5039 18.9043 25.7497 19.4978 25.7496 20.1167C25.7494 20.7355 25.5035 21.329 25.0658 21.7665L21.7665 25.0658Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M25.0833 25.0833L23.45 23.45" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4.54999 4.54999L2.91666 2.91666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.47132 14.896C7.03365 15.3337 6.44003 15.5795 5.82107 15.5795C5.20211 15.5795 4.60849 15.3337 4.17082 14.896C3.73315 14.4583 3.48726 13.8647 3.48726 13.2457C3.48726 12.6268 3.73315 12.0332 4.17082 11.5955L6.23349 9.53399C6.01677 9.75062 5.75951 9.92246 5.47639 10.0397C5.19327 10.1569 4.88983 10.2172 4.58341 10.2171C3.96455 10.217 3.37109 9.97108 2.93357 9.5334C2.71693 9.31669 2.5451 9.05943 2.42789 8.77631C2.31067 8.49319 2.25037 8.18975 2.25043 7.88332C2.25054 7.26447 2.49648 6.67101 2.93415 6.23349L6.23349 2.93415C6.67101 2.49648 7.26447 2.25054 7.88332 2.25043C8.18975 2.25037 8.49319 2.31067 8.77631 2.42789C9.05943 2.5451 9.31669 2.71693 9.5334 2.93357C9.75012 3.15021 9.92204 3.40741 10.0394 3.69049C10.1567 3.97357 10.2171 4.27698 10.2171 4.58341C10.2172 4.88983 10.1569 5.19327 10.0397 5.47639C9.92246 5.75951 9.75062 6.01677 9.53399 6.23349L11.5955 4.17082C12.0332 3.73315 12.6268 3.48726 13.2457 3.48726C13.8647 3.48726 14.4583 3.73315 14.896 4.17082C15.3337 4.60849 15.5795 5.20211 15.5795 5.82107C15.5795 6.44003 15.3337 7.03365 14.896 7.47132L7.47132 14.896Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    <?php endif; ?>
                    <span><?= $progress['text'] ?></span>
                </div>
            <?php else: ?>
                <p class="bottom-text">
                    Measure your BMI<br />and see how it progresses over time
                </p>
            <?php endif; ?>
        </a>
        <a href="/rat/bmi" class="grid-tile">
            <div class="top">
                <h2>BMI Calculator</h2>
            </div>
            <div class="bottom-text"><?= $bmi_text ?></div>
        </a>
        <?php if ($customer->trainer): ?>
            <a href="/rat/trainer" class="grid-tile">
                <div class="top">
                    <h2>Trainer</h2>
                </div>
                <div class="trainer">
                    <img src="<?= $trainer_data['avatar'] ?>" alt="Image of <?= $trainer_data['name'] ?>" class="avatar">
                    <span><?= $trainer_data['name'] ?></span>
                </div>
            </a>
        <?php else: ?>
            <div class="grid-tile grayed">
                <div class="top">
                    <h2>My Trainer</h2>
                    <p>Not Assigned</p>
                </div>
                <p class="bottom-text">Please contact the gym admin to assign a trainer for you.</p>
            </div>
        <?php endif; ?>
        <a href="/rat/subscription/index.php" class="grid-tile <?php echo $plan_remaining_days < 8 ? 'red' : '' ?>">
            <div class="top">
                <h2>
                    Subscription
                </h2>
                <p><?= $plan->name ?> Plan</p>
            </div>
            <p class="bottom-text">
                <?php if ($plan_remaining_days < 8): ?>
                    Your plan will expire wihtin <?= $plan_remaining_days ?> days!
                <?php else: ?>
                    <?= $plan_remaining_days ?> days remaining
                <?php endif; ?>
            </p>
        </a>
        <a href="/rat/gym/index.php" class="grid-tile">
            <div class="top">
                <h2>About Gym</h2>
            </div>
            <p class="bottom-text"><?= $settings->gym_name ?? "It's where you workout" ?></p>
        </a>
        <a href="/rat/complaint" class="complaint-tile grayed">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
            </svg>
            <h2>Make Complaint</h2>
        </a>
    </div>

</main>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>