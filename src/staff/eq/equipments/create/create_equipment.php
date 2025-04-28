<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Equipment.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert( "Method not allowed",  "/staff/eq/equipments");
    exit;
}

if (!isset($_SESSION['equipment'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
    exit;
}

$name = $_POST['equipment_name'];
$description = $_POST['equipment_description'];
$type = $_POST['equipment_category'];
$quantity    = isset($_POST['equipment_quantity']) ? (int) $_POST['equipment_quantity'] : 0;
$status = $_POST['equipment_status'];
$manufacturer = $_POST['equipment_manufacturer'];
$purchase_date = $_POST['equipment_purchase_date'] ?? null;

//For Validation
$errors=[];

if (empty($name)) $errors[] = "Name is required.";
if (empty($type)) $errors[] = "Type is required.";
if ($quantity === null || !is_numeric($quantity)) $errors[] = "Quantity is required and must be a number.";
if (empty($status) || !in_array($status, ['available', 'not available'])) $errors[] = "Status is required and must be either 'available' or 'not available'.";
if (empty($description)) $errors[] = "Description is required.";
if (empty($manufacturer)) $errors[] = "Manufacturer is required.";
if (!empty($purchase_date)) {
    try {
        $purchase_date = new DateTime($purchase_date);
    } catch (Exception $e) {
        $errors[] = "Invalid purchase date format.";
    }
}

//Image Uploading
$image = $_FILES['equipment_image']['name'] ? $_FILES['equipment_image'] : null;
if ($image) {
    try {
        $image = upload_file("staff-equipment-images", $image);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/eq/equipments/create");
        exit;
    }
}

$equipment = unserialize($_SESSION['equipment']);

//Existing image and delete if find
if ($equipment->image && $image) {
    $old_image = $equipment->image;
    try {
        delete_file($old_image);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
    $equipment->image = $image;
}

$equipment->name = $name;
$equipment->description = $description;
$equipment->type = $type;
$equipment->quantity = $quantity;
$equipment->status = $status;
$equipment->manufacturer = $manufacturer;
$equipment->purchase_date = $purchase_date ?? new DateTime();
$equipment->image = $image ?? $equipment->image;

$_SESSION['equipment'] = serialize($equipment);

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/eq/equipments/create");
    exit;
}

try {
    $equipment->save();
} catch (PDOException $e) {
    $_SESSION['equipment'] = $equipment;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['equipment']->name = "";
        redirect_with_error_alert("Failed to create equipment due to an error: Equipment with the same name already exists", "/staff/eq/equipments/create");
        exit;
    }
    redirect_with_error_alert("Failed to create equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/create");
    exit;
}

unset($_SESSION['equipment']);

redirect_with_success_alert("Equipment created successfully", "/staff/eq/equipments");
exit;
?>
