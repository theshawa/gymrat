<?php

require_once __DIR__ . "/../../models/Customer.php";
require_once __DIR__ . "/../../models/Trainer.php";
require_once __DIR__ . "/../../models/TrainerLogRecord.php";
require_once __DIR__ . "/../../Database.php";

function seed_customer_progress(Faker\Generator $faker)
{
    clear_data();
    $db = Database::get_conn();
    $customer_model = new Customer();
    $trainer_model = new Trainer();

    require_once __DIR__ . "/../data/customer_progress.php";

    $db->beginTransaction();
    try {
        $customers_list = $customer_model->get_all();
        $customer_ids = array_map(function ($customer) {
            return $customer->id;
        }, $customers_list);
        $trainers_list = $trainer_model->get_all();
        $trainer_ids = array_map(function ($trainer) {
            return $trainer->id;
        }, $trainers_list);

        foreach ($records as $record) {
            $customer_id = $faker->randomElement($customer_ids);
            $trainer_id = $faker->randomElement($trainer_ids);
            $created_at = $faker->dateTimeBetween('-1 year', 'now');

            $sql = "INSERT INTO customer_progress (customer_id, trainer_id, message, performance_type, created_at) VALUES (:customer_id, :trainer_id, :message, :performance_type, :created_at)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                'customer_id' => $customer_id,
                'trainer_id' => $trainer_id,
                'message' => $record['message'],
                'performance_type' => $record['performance_type'],
                'created_at' => $created_at->format('Y-m-d H:i:s'),
            ]);
            $stmt->closeCursor();
        }
        $db->commit();
        echo "Seeded " . count($records) . " customer_progress data<br/>";
    } catch (\Throwable $th) {
        $db->rollBack();
        die("Error seeding customer_progress data: " . $th->getMessage() . "<br/>");
    }
}


function clear_data()
{
    try {
        $db = Database::get_conn();
        $sql = "SET FOREIGN_KEY_CHECKS=0; DELETE FROM customer_progress; SET FOREIGN_KEY_CHECKS=1;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();
        echo "Cleared customer_progress data<br/>";
    } catch (\Throwable $th) {
        die("Error clearing customer_progress data: " . $th->getMessage() . "<br/>");
    }
}
