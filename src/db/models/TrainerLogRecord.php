<?php

require_once __DIR__ . "/../Model.php";

class TrainerLogRecord extends Model
{
    protected $table = "customer_progress";

    public int $id;
    public int $customer_id;
    public int $trainer_id;
    public string $message;
    public string $performance_type;
    public DateTime $created_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->customer_id = $data['customer_id'] ?? 0;
        $this->trainer_id = $data['trainer_id'] ?? 0;
        $this->message = $data['message'] ?? "";
        $this->performance_type = $data['performance_type'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function get_all_of_user_with_trainer(int $user, int $trainer)
    {
        $sql = "SELECT * FROM $this->table WHERE customer_id = :customer_id and trainer_id = :trainer_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $user,
            'trainer_id' => $trainer
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new TrainerLogRecord();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function delete_all_of_user(int $user)
    {
        $sql = "DELETE FROM $this->table WHERE customer_id = :customer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $user
        ]);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (customer_id, trainer_id, message, performance_type) VALUES (:customer_id, :trainer_id, :message, :performance_type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $this->customer_id,
            'trainer_id' => $this->trainer_id,
            'message' => $this->message,
            'performance_type' => $this->performance_type,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function get_perfomance_type_text()
    {
        switch ($this->performance_type) {
            case 'try_harder':
                return "Try Harder";
            case 'well_done':
                return "Well Done";
            default:
                return "Unknown";
        }
    }
}
