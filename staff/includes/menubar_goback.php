<?php

session_start();
session_unset();
session_destroy();

$redirect = isset($_GET['goBackTo']) ? $_GET['goBackTo'] : 'index.php';

header("Location: $redirect");
exit();