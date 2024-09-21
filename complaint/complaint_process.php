<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

var_dump($_POST);
