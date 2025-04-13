<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/eq/equipments");
}

$id = $_POST['equipment_id'];

require_once "../../../../db/models/Equipment.php";

$equipment = new Equipment();
$equipment->get_by_id($id);

try {
    $equipment->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/view?id=" . $id);
}

unset($_SESSION['equipment']);
unset($_SESSION['equipment_id']);

redirect_with_success_alert("Equipment deleted successfully", "/staff/eq/equipments");