<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

$bmi = floatval(htmlspecialchars($_POST['bmi']));
$weight = floatval(htmlspecialchars($_POST['weight']));
$height = floatval(htmlspecialchars($_POST['height']));
$age = intval(htmlspecialchars($_POST['age']));

$user = $_SESSION['auth']['id'];

require_once "../../db/models/BmiRecord.php";

$record = new BmiRecord();
$record->fill([
    "user" => $user,
    "bmi" => $bmi,
    "weight" => $weight,
    "height" => $height,
    "age" => $age,
]);

try {
    $record->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to save BMI record due to error: " . $e->getMessage(), "./");
}

redirect_with_success_alert("BMI record saved successfully", "./");
