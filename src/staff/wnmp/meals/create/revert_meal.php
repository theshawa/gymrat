<?php

session_start();

$id = &$_SESSION['meal_id'];

require_once "../../../../db/models/Meal.php";

$originalMeal = new Meal();
$originalMeal->fill([]);

$_SESSION['meal'] = serialize($originalMeal);

redirect_with_success_alert("Meal reverted successfully", "/staff/wnmp/meals/edit/?id=" . $id);
exit;
