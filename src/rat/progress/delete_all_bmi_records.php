<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', '../progress');
}

require_once "../../db/models/BmiRecord.php";

$record = new BmiRecord();

try {
    $record->delete_all_of_user($_SESSION['auth']['id']);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to clear all records due to error: " . $e->getMessage(), "./");
}

redirect_with_success_alert("All BMI records deleted successfully", "./");
