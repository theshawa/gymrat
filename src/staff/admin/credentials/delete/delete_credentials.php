<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Staff.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/credentials");
    exit;
}

$id = $_POST['staff_id'];

$staff = new Staff();
$staff->id = (int)$id;
try{
    $staff->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch staff: " . $e->getMessage(), "/staff/admin/credentials/view/index.php?id=$id");
    exit;
}

if ($staff->role === "admin") {
    redirect_with_error_alert("Admin roles cannot be deleted.", "/staff/admin/credentials/view/index.php?id=$id");
    exit;
}

try {
    $staff->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete credential due to an error: " . $e->getMessage(), "/staff/admin/credentials/view/index.php?id=" . $id);
    exit;
}

redirect_with_success_alert("Credential deleted successfully", "/staff/admin/credentials");
exit;