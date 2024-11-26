<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/eq/exercises");
}
if ($_POST['exercise_id'] !== $_SESSION['exercise_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/eq/exercises/edit?id=" . $_SESSION['equipment_id']);
}

$id = $_POST['equipment_id'];
$name = $_POST['equipment_name'];
$description = $_POST['equipment_description'];
$manufacturer = $_POST['equipment_manufacturer'];
$type = $_POST['equipment_type'];
$purchase_date = $_POST['equipment_purchase_date'];
$last_maintenance = $_POST['equipment_last_maintenance'];

require_once "../../../../db/models/Equipment.php";

$newEquipment = new Equipment();
$newEquipment->fill([
    'id' => $id,
    'name' => $name,
    'description' => $description,
    'manufacturer' => $manufacturer,
    'type' => $type,
    'purchase_date' => $purchase_date,
    'last_maintenance' => $last_maintenance,
]);

try {
    $newEquipment->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to edit equipment due to an error: Equipment with the same name already exists", "/staff/eq/equipments/edit?id=" . $id);
    }
    redirect_with_error_alert("Failed to update equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/edit?id=" . $id);
}
unset($_SESSION['equipment']);
unset($_SESSION['equipment_id']);

redirect_with_success_alert("Equipment updated successfully", "/staff/eq/equipments/view?id=" . $id);



