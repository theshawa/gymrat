<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Staff.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/credentials");
    exit;
}

$name = $_POST['staff_name'] ?? null;
$email = $_POST['staff_email'] ?? null;
$password = $_POST['staff_password'] ?? null;
$confirm_password = $_POST['staff_confirm_password'] ?? null;
$role = $_POST['staff_role'] ?? null;

$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (empty($email)) $errors[] = "Email is required.";
if (empty($password)) $errors[] = "Password is required.";
if ($password !== $confirm_password) $errors[] = "Passwords do not match.";
if (!in_array($role, ["wnmp", "eq"])) $errors[] = "Invalid role selected.";

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/credentials/create");
    exit;
}

$staff = new Staff();
$staff->name = $name;
$staff->email = $email;
$staff->password = $password;
$staff->role = $role;

try {
    $staff->create();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to create staff due to an error: Email already exists", "/staff/admin/credentials/create");
        exit;
    }
    redirect_with_error_alert("Failed to create staff due to an error: " . $e->getMessage(), "/staff/admin/credentials/create");
    exit;
}

redirect_with_success_alert("Staff created successfully", "/staff/admin/credentials");
exit;