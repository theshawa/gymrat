<?php

session_start();

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die("method not allowed");
}

$id = $_POST['id'];
$status = $_POST['status'];

require_once "../../../db/models/MembershipPlan.php";

$membershipPlan = new MembershipPlan();
$membershipPlan->get_by_id($id);
$membershipPlan->is_locked = (int) $status;
$membershipPlan->save();

$_SESSION['alert'] = "Membership plan " . ($status == 1 ? "locked" : "unlocked") . " successfully";

header("Location: index.php");
