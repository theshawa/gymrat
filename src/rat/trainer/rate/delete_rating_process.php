<?php


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Method not allowed");
}

require_once "../../../db/models/TrainerRating.php";
require_once "../../../alerts/functions.php";

$ratingId = (int) htmlspecialchars($_POST['id']);

$trainerRating = new TrainerRating();
$trainerRating->fill([
    'id' => $ratingId,
]);
try {
    $trainerRating->delete();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to delete trainer rating: " . $e->getMessage(), "./");
    exit;
}

redirect_with_success_alert("Rating deleted successfully.", "./");
