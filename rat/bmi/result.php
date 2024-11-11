<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

$gender = $_POST['gender'];
$height = (float)$_POST['height'];
$weight = (float)$_POST['weight'];
$age = (float)$_POST['age'];

$bmi = $weight / (($height / 100) ** 2);
$bmi = round($bmi, 2);

$category = "good";
$message = "";


$pageConfig = [
    "title" => "BMI Calculator Result",
    "styles" => ["./bmi.css"],
    "titlebar" => [
        "back_url" => "/rat/bmi/index.php",
    ],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<main id="result">
    <h1 class="alt"><?php echo $bmi ?></h1>
    <input type="range" value="<?php echo $bmi ?>" disabled min="10" max="50">
    <h2 class="<?php echo ($category === "worst" ? "red" : ($category === "bad" ? "yellow" : "green")) ?>"><?php echo $message ?></h2>
    <form action="save_process.php" method="post">
        <input type="hidden" name="bmi" value="<?php echo $bmi ?>">
        <input type="hidden" name="age" value="<?php echo $gender ?>">
        <input type="hidden" name="weight" value="<?php echo $weight ?>">
        <input type="hidden" name="height" value="<?php echo $height ?>">
        <input type="hidden" name="gender" value="<?php echo $age ?>">
        <button class="btn secondary">Save Record</button>
    </form>
    <a href="/rat/bmi/index.php" class="btn">Calculate Again</a>
</main>
<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>