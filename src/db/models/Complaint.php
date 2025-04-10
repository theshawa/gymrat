<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/Trainer.php";
require_once __DIR__ . "/Customer.php";

class Complaint extends Model
{
    protected $table = "complaints";

    public int $id;
    public string $type;
    public string $description;
    public int $user_id;
    public DateTime $created_at;
    public int $is_created_by_trainer;
    private string $name;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['type'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->user_id = $data['user_id'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->is_created_by_trainer = $data['is_created_by_trainer'] ?? 0;
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $complaint = new Complaint();
            $complaint->fill($item);
            return $complaint;
        }, $items);
    }

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("Complaint not found");
        }
        $this->fill($item);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (type, description, user_id, is_created_by_trainer) VALUES (:type, :description, :user_id, :is_created_by_trainer)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'is_created_by_trainer' => $this->is_created_by_trainer
        ]);
    }

    public function update_name()
    {
        if ($this->is_created_by_trainer) {
            $trainer = new Trainer();
            $trainer->id = $this->user_id;
            if ($trainer->get_by_id()) {
                $this->name = $trainer->fname . ' ' . $trainer->lname;
            }
        } else {
            $customer = new Customer();
            $customer->id = $this->user_id;
            if ($customer->get_by_id()) {
                $this->name = $customer->fname . ' ' . $customer->lname;
            }
        }
    }
}
