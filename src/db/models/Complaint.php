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
    public string $user_type;
    public DateTime $created_at;
    public ?string $review_message;
    public ?DateTime $reviewed_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['type'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->user_id = $data['user_id'] ?? 0;
        $this->user_type = $data['user_type'] ?? "rat";
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->review_message = $data['review_message'] ?? null;
        $this->reviewed_at = isset($data['reviewed_at']) ? new DateTime($data['reviewed_at']) : null;
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
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
        try {
            $sql = "INSERT INTO $this->table (type, description, user_id, user_type) 
                    VALUES (:type, :description, :user_id, :user_type)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'type' => $this->type,
                'description' => $this->description,
                'user_id' => $this->user_id,
                'user_type' => $this->user_type,
            ]);

            $this->id = $this->conn->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Provide more detailed error message
            throw new PDOException("Error creating complaint: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function review()
    {
        $sql = "UPDATE $this->table SET review_message = :review_message, reviewed_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'review_message' => $this->review_message,
            'id' => $this->id,
        ]);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

    public function get_all_of_user(int $user_id, string $user_type)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id AND user_type = :user_type ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $complaint = new Complaint();
            $complaint->fill($item);
            return $complaint;
        }, $items);
    }

    public function delete_all_reviewed()
    {
        $sql = "DELETE FROM $this->table WHERE reviewed_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }
}