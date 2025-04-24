<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

require_once __DIR__ . "/../../vendor/autoload.php";

require_once __DIR__ . "/../Database.php";

$faker = Faker\Factory::create();


$db = Database::get_conn();

require_once "./seeders/exercises.php";
seed_exercises($faker);
