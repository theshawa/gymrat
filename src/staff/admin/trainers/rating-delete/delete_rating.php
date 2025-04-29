<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/trainers");
    exit;
}

$rating_id = $_POST['rating_id'];
$trainer_id = $_POST['trainer_id'];

require_once "../../../../db/models/TrainerRating.php";

$trainerRating = new TrainerRating();
$trainerRating = $trainerRating->get_by_id($rating_id);


try {
    $trainerRating->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete rating due to an error: " . $e->getMessage(), "/staff/admin/trainers/view/index.php?id=" .$trainer_id);
    exit;
}


redirect_with_success_alert("Rating deleted successfully", "/staff/admin/trainers/ratings/index.php?id=" . $trainer_id);
exit;
