<?php

require_once __DIR__ . "/../Model.php";

class BmiRecord extends Model
{
    protected $table = "bmi_records";

    public int $user;
    public DateTime $created_at;
    public float $bmi;
    public float $weight;
    public float $height;
    public int $age;

    public function fill(array $data)
    {
        $this->user = $data['user'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->bmi = $data['bmi'] ?? 0;
        $this->weight = $data['weight'] ?? 0;
        $this->height = $data['height'] ?? 0;
        $this->age = $data['age'] ?? 0;
    }

    public function get_all_of_user(int $user): array
    {
        $sql = "SELECT * FROM $this->table WHERE user = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $user
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new BmiRecord();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function get_last_of_user()
    {
        $sql = "SELECT * FROM $this->table WHERE user = :user ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $this->user
        ]);
        $item = $stmt->fetch();
        if ($item) {
            $this->fill($item);
        }
    }

    public function delete_all_of_user(int $user)
    {
        $sql = "DELETE FROM $this->table WHERE user = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $user
        ]);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (user, bmi, weight, height, age) VALUES (:user, :bmi, :weight, :height, :age)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $this->user,
            'bmi' => $this->bmi,
            'weight' => $this->weight,
            'height' => $this->height,
            'age' => $this->age,
        ]);
    }
}
