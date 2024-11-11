<?php

session_start();

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die("method not allowed");
}

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$duration = $_POST['duration'];

$price = (float) $price;
$duration = (int) $duration;

require_once "../../../../db/models/MembershipPlan.php";

$membershipPlan = new MembershipPlan();
$membershipPlan->fill(null, $name, $description, $price, $duration);
$membershipPlan->save();
$_SESSION['alert'] = "Membership plan created successfully";

header("Location: ../index.php");
