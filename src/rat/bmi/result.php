<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$height = (float)htmlspecialchars($_POST['height']);
$weight = (float)htmlspecialchars($_POST['weight']);
$age = (float)htmlspecialchars($_POST['age']);

$bmi = $weight / (($height / 100) ** 2);
$bmi = round($bmi, 2);


require_once "./functions.php";

$category = get_bmi_classification($bmi);

$pageConfig = [
    "title" => "BMI Calculator Result",
    "styles" => ["./bmi.css"],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "/rat/bmi/index.php",
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main id="result">
    <h1 class="alt"><?= $bmi ?></h1>
    <input type="range" value="<?php echo $bmi ?>" disabled min="10" max="50">
    <h2 class="<?= $category['bad'] ? "bad" : "normal" ?>"><?= $category['type'] ?></h2>
    <p class="paragraph"><?= $category['instruction'] ?></p>
    <a target="_blank" href="https://www.who.int/europe/news-room/fact-sheets/item/a-healthy-lifestyle---who-recommendations" class="article-link">Discover the Facts About BMI and Your Health
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
        </svg>
    </a>
    <form action="save_process.php" method="post">
        <input type="hidden" name="bmi" value="<?php echo $bmi ?>">
        <input type="hidden" name="weight" value="<?php echo $weight ?>">
        <input type="hidden" name="height" value="<?php echo $height ?>">
        <input type="hidden" name="age" value="<?php echo $age ?>">
        <button class="btn">Save Record</button>
    </form>
    <a href="/rat/bmi/index.php" class="btn secondary">Calculate Again</a>

</main>
<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>