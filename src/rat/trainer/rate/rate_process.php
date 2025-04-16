<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Method not allowed");
}


require_once "../../../db/models/TrainerRating.php";
require_once "../../../db/models/Trainer.php";
require_once "../../../db/models/Customer.php";
require_once "../../../alerts/functions.php";

$customer = new Customer();
$customer->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $customer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to get customer: " . $e->getMessage(), "./");
    exit;
}

$trainerId = $customer->trainer;
$rating = (int) htmlspecialchars($_POST['rate']);
$review = htmlspecialchars($_POST['review']);

$trainerRating = new TrainerRating();
$trainerRating->fill([
    'customer_id' => $_SESSION['auth']['id'],
    'trainer_id' => $trainerId,
    'rating' => $rating,
    'review' => $review,
]);
try {
    $trainerRating->create();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to create trainer rating: " . $e->getMessage(), "./");
    exit;
}

redirect_with_success_alert("Thank you for sharing your feedback! Your rating has been submitted successfully.", "../");
