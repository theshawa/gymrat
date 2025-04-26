<?php

require_once __DIR__ . "/../Model.php";

class CustomerInitialData extends Model
{
    protected $table = "customer_initial_data";

    public int $customer_id;
    public string $gender;
    public string $goal;
    public string $other_goal;
    public int $age;
    public float $height;
    public float $weight;
    public string $physical_activity_level;
    public string $dietary_preference;
    public string $allergies;
    public DateTime $created_at;

    public function fill(array $data)
    {
        $this->customer_id = $data['customer_id'] ?? 0;
        $this->gender = $data['gender'] ?? '';
        $this->age = $data['age'] ?? 0;
        $this->goal = $data['goal'] ?? '';
        $this->other_goal = $data['other_goal'] ?? '';
        $this->height = $data['height'] ?? 0;
        $this->weight = $data['weight'] ?? 0;
        $this->physical_activity_level = $data['physical_activity_level'] ?? '';
        $this->dietary_preference = $data['dietary_preference'] ?? '';
        $this->allergies = $data['allergies'] ?? '';
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $data = new CustomerInitialData();
            $data->fill($item);
            return $data;
        }, $items);
    }

    public function get_by_id(int $customer_id)
    {
        $sql = "SELECT * FROM $this->table WHERE customer_id = :customer_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['customer_id' => $customer_id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("Row not found");
        }
        $this->fill($item);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (customer_id, gender, goal, other_goal, age, height, weight, physical_activity_level, dietary_preference, allergies) 
        VALUES (:customer_id, :gender, :goal, :other_goal, :age, :height, :weight, :physical_activity_level, :dietary_preference, :allergies)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $this->customer_id,
            'gender' => $this->gender,
            'goal' => $this->goal,
            'other_goal' => $this->other_goal,
            'age' => $this->age,
            'height' => $this->height,
            'weight' => $this->weight,
            'physical_activity_level' => $this->physical_activity_level,
            'dietary_preference' => $this->dietary_preference,
            'allergies' => $this->allergies,
        ]);
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET 
            gender = :gender, 
            goal = :goal, 
            other_goal = :other_goal, 
            age = :age, 
            height = :height, 
            weight = :weight, 
            physical_activity_level = :physical_activity_level, 
            dietary_preference = :dietary_preference, 
            allergies = :allergies 
        WHERE customer_id = :customer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'gender' => $this->gender,
            'goal' => $this->goal,
            'other_goal' => $this->other_goal,
            'age' => $this->age,
            'height' => $this->height,
            'weight' => $this->weight,
            'physical_activity_level' => $this->physical_activity_level,
            'dietary_preference' => $this->dietary_preference,
            'allergies' => $this->allergies,
            'customer_id' => $this->customer_id,
        ]);
    }
}
