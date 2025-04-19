<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Equipment.php";
require_once "../../../../uploads.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/eq/equipments");
    exit;
}
if($_POST['equipment_id'] !== $_SESSION['equipment_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/eq/equipments/edit?id=" . $_SESSION['equipment_id']);
    exit;
}

$id = $_POST['equipment_id'];
$name = $_POST['equipment_name'];
$description = $_POST['equipment_description'];
$category = $_POST['equipment_category'];
$quantity = $_POST['equipment_quantity'];
$status = $_POST['equipment_status'];

// Validation
$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (empty($description)) $errors[] = "Description is required.";
if (empty($category)) $errors[] = "Category is required.";
if (empty($quantity)) $errors[] = "Quantity is required.";
if (empty($status)) $errors[] = "Status is required.";

// image upload
$image = $_FILES['equipment_image']['name'] ? $_FILES['equipment_image'] : null;
if ($image) {
    try {
        $image = upload_file("staff-equipment-images", $image);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/eq/equipments/edit?id=" . $id);
        exit;
    }
}

$equipment = unserialize($_SESSION['equipment']);

// check for existing image and delete if found
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
$equipment->category = $category;
$equipment->quantity = $quantity;
$equipment->status = $status;
$equipment->image = $image ?? $equipment->image;

$_SESSION['equipment'] = serialize($equipment);

if(!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/eq/equipments/edit?id=" . $id);
    exit;
}

try {
    $equipment->save($image);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to edit equipment due to an error: Equipment with the same name already exists", "/staff/eq/equipments/edit?id=" . $id);
    }
    redirect_with_error_alert("Failed to update equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/edit?id=" . $id);
    exit;
}

unset($_SESSION['equipment']);
unset($_SESSION['equipment_id']);

redirect_with_success_alert("Equipment updated successfully", "/staff/eq/equipments/view?id=" . $id);
?>
