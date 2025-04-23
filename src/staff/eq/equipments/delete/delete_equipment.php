<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert( "Method not allowed", "/staff/eq/equipments");
}

$id=$_POST['equipment_id'];

require_once "../../../../db/models/Equipment.php";

$equipment = new Equipment();
$equipment->get_by_id($id);
$image = $equipment->image;

try {
    $equipment->delete();
} catch (PDOException $e) {
    if ($e->getCode() === "23000" && strpos($e->getMessage(), '1451') !== false) {
        redirect_with_error_alert("Failed to delete equipment because it is associated with records. Please remove the associations first.", "/staff/eq/equipments/view?id=" . $id);
        exit;
    }
    redirect_with_error_alert("Failed to delete equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/view?id=" . $id);
    exit;
}

if ($image) {
    try {
        delete_file($image);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete equipment image due to an error: " . $th->getMessage();
    }
}

unset($_SESSION['equipment']);
unset($_SESSION['equipment_id']);

redirect_with_success_alert("Equipment Deleted Successfully", "/staff/eq/equipments");
?>
