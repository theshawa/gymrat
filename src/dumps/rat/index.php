<?php

require_once "../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$workoutSession =  null;
if (isset($_SESSION['workout_session'])) {
    require_once "../db/models/WorkoutSession.php";
    $workoutSession = new WorkoutSession();
    $workoutSession->fill([
        'session_key' => $_SESSION['workout_session']
    ]);
    require_once "../db/models/Settings.php";
    $settings = new Settings();
    try {
        $settings->get_all();
    } catch (\Throwable $th) {
        die("Failed to get settings: " . $th->getMessage());
    }
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

$pageConfig = [
    "title" => "Home",
    "styles" => [
        "./home copy.css"
    ],
    "scripts" => [
        "./workout/workout-timer.js"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Welcome!",
    ],
];

$fname = $_SESSION['auth']['fname'];
$pageConfig['titlebar']['title'] = "Hi, $fname!";

require_once "./includes/header.php";
require_once "./includes/titlebar.php";

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

<main>
    <div class="grid">
        <?php require_once "../uploads.php"; ?>
        <div class="gym-banner" style="background-image: linear-gradient(rgba(9, 9, 11,0) ,rgba(9, 9, 11,0.6),rgba(9, 9, 11,1)) , url(<?= get_file_url("default-images/default-gym-banner.png") ?>);">
            <h1 class="gym-name">
                Dhamya Fitness Centre
            </h1>
            <p class="paragraph">
                Your fitness journey starts here!
            </p>
            <!-- <a href="/rat/subscription/index.php" class="gym-membership <?= $plan_remaining_days <= 7 ? 'danger' : '' ?>">Your <?= $plan->name ?> plan will expire in <?= $plan_remaining_days ?> days</a> -->
        </div>
        <?php if ($customer->workout): ?>
            <div class="tile with-sub-link <?php echo $workoutSession ? 'red' : 'green' ?>">
                <a href="/rat/workout/index.php" class="sub-link">
                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0503 19.9497C9.75743 19.6568 9.75743 19.182 10.0503 18.8891L17.5725 11.3669L11.8328 11.5072C11.4187 11.5153 11.0764 11.1862 11.0682 10.7721C11.0601 10.3579 11.3893 10.0156 11.8034 10.0075L19.4048 9.83072C19.6088 9.82672 19.8056 9.90599 19.9498 10.0502C20.0941 10.1945 20.1733 10.3913 20.1693 10.5953L19.9926 18.1967C19.9845 18.6108 19.6421 18.94 19.228 18.9318C18.8139 18.9237 18.4847 18.5814 18.4929 18.1673L18.6331 12.4276L11.111 19.9497C10.8181 20.2426 10.3432 20.2426 10.0503 19.9497Z" fill="#FAFAFA" />
                    </svg>
                </a>
                <a href="/rat/workout/index.php<?php echo $workoutSession ? '' : '?start=true' ?>" class="content">
                    <?php if ($workoutSession): ?>
                        <span class="sub-text workout-timer"></span>
                    <?php endif; ?>
                    <span><?php echo $workoutSession ? 'End' : 'Start' ?><br />Workout</span>
                </a>
            </div>
        <?php else: ?>
            <div class="tile disabled">
                <span>Workout<br />Not Assigned</span>
            </div>
        <?php endif; ?>
        <a href="/rat/gym-traffic/index.php" class="tile">
            <span>
                Check<br />
                Gym Traffic
            </span>
        </a>
        <?php if ($customer->meal_plan): ?>
            <a href="/rat/meal-plan/index.php" class="tile">
                <span>
                    My<br />
                    Meal Plan
                </span>
            </a>
        <?php else: ?>
            <div class="tile disabled">
                <span>Meal Plan<br />Not Assigned</span>
            </div>
        <?php endif; ?>
        <?php if ($customer->trainer): ?>
            <a href="/rat/trainer" class="tile">
                <span>
                    My<br />
                    Trainer
                </span>
            </a>
        <?php else: ?>
            <div class="tile disabled">
                <span>Trainer<br />Not Assigned</span>
            </div>

        <?php endif; ?>
        <a href="/rat/progress/index.php" class="tile">
            <span>
                My<br />
                Progress
            </span>
        </a>
        <a href="/rat/bmi/index.php" class="tile">
            <span>
                BMI<br />
                Calculator
            </span>
        </a>
        <a href="/rat/complaint/index.php" class="tile gray full-width">
            <span>
                Make Complaint
            </span>
        </a>

        <a href="/rat/subscription/index.php" class="tile <?php echo $plan_remaining_days < 8 ? 'red' : '' ?>">
            <?php if ($plan_remaining_days < 8): ?>
                <span class="sub-text">Your plan will expire wihtin <?= $plan_remaining_days ?> days!</span>
            <?php else: ?>
                <span class="sub-text"><?= $plan_remaining_days ?> days remaining</span>
            <?php endif; ?>
            <span>
                My<br />
                Subscription
            </span>
        </a>
        <a href="/rat/support/index.php" class="tile">
            <span>
                Contact<br />
                Support
            </span>
        </a>
    </div>
</main>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>