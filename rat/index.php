<?php
$pageConfig = [
    "title" => "Home",
    "styles" => [
        "./home.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Welcome!",
    ],
    "need_auth" => true
];

require_once "./includes/header.php";

if (isset($_SESSION['auth'])) {
    $fname = $_SESSION['auth']['fname'];
    $pageConfig['titlebar']['title'] = "Hi, $fname!";
}

require_once "./includes/titlebar.php";

$workoutIsActive = false;
?>

<main>
    <div class="grid">
        <div class="tile with-sub-link <?php echo $workoutIsActive ? 'red' : 'green' ?>">
            <a href="/rat/workout/index.php" class="sub-link">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0503 19.9497C9.75743 19.6568 9.75743 19.182 10.0503 18.8891L17.5725 11.3669L11.8328 11.5072C11.4187 11.5153 11.0764 11.1862 11.0682 10.7721C11.0601 10.3579 11.3893 10.0156 11.8034 10.0075L19.4048 9.83072C19.6088 9.82672 19.8056 9.90599 19.9498 10.0502C20.0941 10.1945 20.1733 10.3913 20.1693 10.5953L19.9926 18.1967C19.9845 18.6108 19.6421 18.94 19.228 18.9318C18.8139 18.9237 18.4847 18.5814 18.4929 18.1673L18.6331 12.4276L11.111 19.9497C10.8181 20.2426 10.3432 20.2426 10.0503 19.9497Z" fill="#FAFAFA" />
                </svg>
            </a>
            <a href="/rat/workout/index.php?<?php echo $workoutIsActive ? 'end=true' : 'start=true' ?>" class="content">
                <span><?php echo $workoutIsActive ? 'End' : 'Start' ?><br />Workout</span>
            </a>
        </div>
        <a href="/rat/gym-traffic/index.php" class="tile">
            <span>
                Check<br />
                Gym Traffic
            </span>
        </a>
        <a href="/rat/meal-plan/index.php" class="tile">
            <span>
                My<br />
                Meal Plan
            </span>
        </a>
        <a href="/rat/trainer/index.php" class="tile">
            <span>
                My<br />
                Trainer
            </span>
        </a>
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
    </div>
</main>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>