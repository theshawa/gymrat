<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('method not allowed');
}

var_dump($_POST);
