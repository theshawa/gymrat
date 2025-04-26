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

    public function get_all_of_user(int $user)
    {
        $sql = "SELECT * FROM $this->table WHERE customer_id = :customer_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $user,
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

    public function update()
    {
        $sql = "UPDATE $this->table SET message = :message, performance_type = :performance_type WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'message' => $this->message,
            'performance_type' => $this->performance_type,
            'id' => $this->id,
        ]);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $item = $stmt->fetch();
        if (!$item) {
            throw new Exception("Log record not found");
        }
        $this->fill($item);
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
