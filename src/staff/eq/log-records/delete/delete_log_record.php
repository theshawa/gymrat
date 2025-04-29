<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert( "Method not allowed", "/staff/eq/equipments");
}

$id=$_POST['log_record_id'];

require_once "../../../../db/models/LogRecord.php";

$log_record = new LogRecord();
$log_record->get_by_id($id);


try {
    $log_record->delete();
} catch (PDOException $e) {
    if ($e->getCode() === "23000" && strpos($e->getMessage(), '1451') !== false) {
        redirect_with_error_alert("Failed to delete equipment because it is associated with records. Please remove the associations first.", "/staff/eq/equipments/view?id=" . $id);
        exit;
    }
    redirect_with_error_alert("Failed to delete equipment due to an error: " . $e->getMessage(), "/staff/eq/equipments/view?id=" . $id);
    exit;
}



unset($_SESSION['log_record']);
unset($_SESSION['log_record_id']);

redirect_with_success_alert("Log Record Deleted Successfully", "/staff/eq/log-records");
?>
