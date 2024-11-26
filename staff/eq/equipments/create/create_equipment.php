<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/eq/equipments");
}

$name = $_POST['equipment_name'];
$type = $_POST['equipment_type'];
$manufacturer = $_POST['equipment_manufacturer'];
$description = $_POST['equipment_description'];
$purchase_date = $_POST['equipment_purchase_date'];
$last_maintenance = $_POST['equipment_last_maintenance'];

require_once "../../../../db/models/Equipment.php";

$newEquipment = new Equipment();
$newEquipment->fill([
    'name' => $name,
    'type' => $type,
    'manufacturer' => $manufacturer,
    'description' => $description,
    'purchase_date' => $purchase_date,
    'last_maintenance' => $last_maintenance
]);

try {
    $newEquipment->save();
} catch (PDOException $e) {
    $_SESSION['equipment'] = $newEquipment;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['equipment']->name = "";
        redirect_with_error_alert("Failed to create equipment due to an error: Equipment with the same name already exists", "/staff/eq/equipments/create");
    }
    redirect_with_error_alert("Failed to create equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/create");
}
unset($_SESSION['equipment']);

redirect_with_success_alert("Equipment created successfully", "/staff/eq/equipments");
