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
if ($bmi < 18.5) {
    $category = "worst";
    $message = "You are underweight.<br/>You should consult a doctor.";
} else if ($bmi < 24.9) {
    $category = "good";
    $message = "You are normal.<br/>Keep it up!";
} else if ($bmi < 29.9) {
    $category = "bad";
    $message = "You are overweight.<br/>You should exercise more.";
} else {
    $category = "worst";
    $message = "You are obese.<br/>You should consult a doctor.";
}

$pageConfig = [
    "title" => "BMI Calculator Result",
    "styles" => ["./bmi.css"],
    "titlebar" => [
        "back_url" => "/index.php",
    ],
];

include_once "../includes/header.php";
include_once "../includes/titlebar.php";

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
        <button href="/bmi/index.php" class="btn secondary">Save Record</button>
    </form>
    <a href="/bmi/index.php" class="btn">Calculate Again</a>
</main>
<?php include_once "../includes/navbar.php" ?>
<?php include_once "../includes/footer.php" ?>