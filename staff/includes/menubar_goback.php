<?php

session_start();
session_unset();
session_destroy();

$redirect = isset($_GET['goBackTo']) ? htmlspecialchars($_GET['goBackTo']) : 'index.php';

header("Location: $redirect");
exit();
