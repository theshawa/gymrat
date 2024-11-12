<?php

session_start();

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die("method not allowed");
}

$id = $_POST['id'];

require_once "../../../db/models/MembershipPlan.php";

$membershipPlan = new MembershipPlan();
$membershipPlan->fill([
    'id' => $id,
]);
$membershipPlan->delete();

$_SESSION['alert'] = "Membership plan deleted successfully";

header("Location: index.php");
