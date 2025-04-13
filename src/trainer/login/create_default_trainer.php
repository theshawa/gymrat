<?php
// Script to create a default trainer account
// Place this file in src/trainer/setup/ directory

require_once __DIR__ . "/../../db/models/Trainer.php";
require_once __DIR__ . "/../../alerts/functions.php";

// Check if script is being run directly
if (php_sapi_name() != 'cli' && !isset($_GET['confirm']) && $_GET['confirm'] != 'yes') {
    die("This script must be run from the command line or with proper confirmation.");
}

try {
    // Create trainer instance
    $trainer = new Trainer();

    // Check if username already exists
    $trainer->username = "john";
    $exists = $trainer->get_by_username();

    if ($exists) {
        echo "A trainer with username 'john' already exists!\n";
        exit(1);
    }

    // Set trainer data
    $trainer->fname = "John";
    $trainer->lname = "Doe";
    $trainer->username = "john";
    $trainer->password = "123456"; // Will be hashed by the save() method
    $trainer->bio = "Default trainer account with expertise in strength training and cardio.";
    $trainer->rating = 4.5;
    $trainer->review_count = 10;

    // Save the trainer
    $trainer->save();

    echo "Default trainer created successfully!\n";
    echo "Username: john\n";
    echo "Password: 123456\n";
    echo "Trainer ID: " . $trainer->id . "\n";

} catch (Exception $e) {
    echo "Error creating default trainer: " . $e->getMessage() . "\n";
    exit(1);
}
?>