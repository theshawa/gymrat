<?php

session_start();

$id = &$_SESSION['equipment_id'];

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$originalEquipment = new Equipment();

try {
    $originalEquipment->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch equipment: " . $e->getMessage(), "/staff/eq");
}

$_SESSION['equipment'] = $originalEquipment;

redirect_with_success_alert("Revert Successful", "/staff/eq/equipments/edit?id=$id");
