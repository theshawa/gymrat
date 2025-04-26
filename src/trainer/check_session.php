<?php
// Place this file at src/trainer/check_session.php

session_start();

// Output session data
echo "<h2>Session Data</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

// Output authentication status
echo "<h2>Authentication Status</h2>";

require_once "../auth-guards.php";
$is_valid = is_auth_valid("trainer");

echo "is_auth_valid('trainer') returns: " . ($is_valid ? 'true' : 'false') . "<br>";

// Check the specific conditions from is_auth_valid function
echo "<h2>Auth Validation Conditions</h2>";
echo "SESSION['auth'] is set: " . (isset($_SESSION['auth']) ? 'Yes' : 'No') . "<br>";

if (isset($_SESSION['auth'])) {
    echo "SESSION['auth']['role'] is set: " . (isset($_SESSION['auth']['role']) ? 'Yes' : 'No') . "<br>";

    if (isset($_SESSION['auth']['role'])) {
        echo "SESSION['auth']['role'] value: " . $_SESSION['auth']['role'] . "<br>";
        echo "Role matches 'trainer': " . ($_SESSION['auth']['role'] === "trainer" ? 'Yes' : 'No') . "<br>";
    }

    echo "SESSION['auth']['session_started_at'] is set: " .
        (isset($_SESSION['auth']['session_started_at']) ? 'Yes' : 'No') . "<br>";

    if (isset($_SESSION['auth']['session_started_at'])) {
        $created_at = $_SESSION['auth']['session_started_at'];
        $now = time();
        $diff = $now - $created_at;

        require_once "../config.php";
        echo "Session age: $diff seconds<br>";
        echo "SESSION_EXPIRE_TIME: $SESSION_EXPIRE_TIME seconds<br>";
        echo "Session expired: " . ($diff > $SESSION_EXPIRE_TIME ? 'Yes' : 'No') . "<br>";
    }
}

// Link to go home
echo "<p><a href='/trainer/'>Go to trainer home</a></p>";
