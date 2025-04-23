<?php

session_start();

require_once "../../../../db/models/Equipment.php";

$newEquipment=new Equipment();

try {
    $newEquipment->fill([]);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch equipment: " . $e->getMessage(), "/staff/eq");
    exit;
}

$_SESSION['equipment'] = serialize($newEquipment);

redirect_with_success_alert("Equipment Reverted Successfully", "/staff/eq/equipments/create");
exit;
?>
