<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "./functions.php";

$traffic = get_traffic();

$pageConfig = [
    "title" => "Live Gym Traffic",
    "styles" => ["./gym-traffic.css"],
    "scripts" => ["./gym-traffic.js"],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Current Traffic At the Gym",
        "back_url" => "../",
    ],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<script>
    const $TRAFFIC = <?= number_format($traffic['value'] * 100, 2) ?>;
</script>

<main>
    <div class="meter">
        <div class="arrow"></div>
    </div>
    <span class="label traffic-value">Loading...</span>

    <div class="data">
        <h1 class="title"><?= $traffic['status_text'] ?></h1>
        <div class="active-users">
            <div class="dot"></div>
            <span><?= $traffic['rat_count_text'] ?></span>
        </div>
        <p class="paragraph">*The traffic values are estimates to give you a general idea and may not reflect exact conditions. Use them as a guide, and remember—you can crush your workout at any time!</p>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>