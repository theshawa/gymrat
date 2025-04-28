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

$equipment_id = $_POST['equipment_id'] ?? null;
$description = $_POST['description'];
$status = $_POST['status'];
//For Validation
$errors=[];


if (empty($description)) $errors[] = "Description is required.";

//Image Uploading
// $image = $_FILES['equipment_image']['name'] ? $_FILES['equipment_image'] : null;
// if ($image) {
//     try {
//         $image = upload_file("staff-equipment-images", $image);
//     } catch (\Throwable $th) {
//         redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/eq/equipments/create");
//         exit;
//     }
// }

$log_record = unserialize($_SESSION['log_record']);

//Existing image and delete if find
// if ($equipment->image && $image) {
//     $old_image = $equipment->image;
//     try {
//         delete_file($old_image);
//     } catch (\Throwable $th) {
//         $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
//     }
//     $equipment->image = $image;
// }

$log_record->equipment_id = (int) $equipment_id;
$log_record->description = $description;
$log_record->status = $status;
$log_record->created_at = new DateTime();

$_SESSION['log_record'] = serialize($log_record);

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/eq/log-records/create");
    exit;
}

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

redirect_with_success_alert("Log Records created successfully", "/staff/eq/equipments");
exit;
?>
