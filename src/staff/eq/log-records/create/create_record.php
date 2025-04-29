<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Equipment.php";
require_once "../../../../db/models/LogRecord.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert( "Method not allowed",  "/staff/eq/equipments");
    exit;
}

if (!isset($_SESSION['log_record'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
    exit;
}

$equipment_ids = $_POST['equipment_ids'] ?? [];
$statuses = $_POST['statuses'] ?? [];
$errors = [];

if (count($equipment_ids) !== count($statuses)) {
    $errors[] = "Mismatch between equipment IDs and statuses.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/eq/log-records/create");
    exit;
}

// Combine equipment IDs and statuses into a JSON string
$equipment_statuses = [];
foreach ($equipment_ids as $index => $equipment_id) {
    $equipment_statuses[] = [
        'equipment_id' => (int) $equipment_id,
        'status' => $statuses[$index]
    ];
}
$description = json_encode($equipment_statuses);

$log_record = unserialize($_SESSION['log_record']);

$log_record->description = $description;
$log_record->created_at = new DateTime();

$_SESSION['log_record'] = serialize($log_record);

try {
    $log_record->save();
} catch (PDOException $e) {
    $_SESSION['log_record'] = $log_record;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['log_record']->name = "";
        redirect_with_error_alert("Failed to create equipment due to an error: Equipment with the same name already exists", "/staff/eq/equipments/create");
        exit;
    }
    redirect_with_error_alert("Failed to create equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/create");
    exit;
}

unset($_SESSION['log_record']);

redirect_with_success_alert("Log Records created successfully", "/staff/eq/log-records");
exit;
?>
