<?php


require_once __DIR__ . "/../../models/Exercise.php";
require_once __DIR__ . "/../../Database.php";

function seed_exercises(Faker\Generator $faker)
{
    throw new Exception("Not implemented");
}

function clear_data()
{
    try {
        $db = Database::get_conn();
        $sql = "SET FOREIGN_KEY_CHECKS=0; DELETE FROM exercises; SET FOREIGN_KEY_CHECKS=1;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();
        echo "Cleared exercises data\n";
    } catch (\Throwable $th) {
        die("Error clearing exercises data: " . $th->getMessage() . "\n");
    }
}
