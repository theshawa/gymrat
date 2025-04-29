<?php

session_start();

if (isset($_SESSION['log_record'])) {
    unset($_SESSION['log_record']);
}

header("Location: /staff/eq/log-records/create");
exit;
?>
