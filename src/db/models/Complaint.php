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
    public ?string $user_name; // For api use only

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
        $this->user_name = $data['user_name'] ?? null; 
    }

    public function get_all(int $sort = -1, int $notReviewed = 0): array
    {
        // sort: 0 = no sort, 1 = ascending, -1 = descending
        // notReviewed: 0 = all, 1 = only unreviewed
        $sql = "SELECT * FROM $this->table";
        if ($notReviewed === 1) {
            $sql .= " WHERE review_message IS NULL";
        }
        if ($sort === 1) {
            $sql .= " ORDER BY created_at ASC"; 
        } elseif ($sort === -1) {
            $sql .= " ORDER BY created_at DESC"; 
        }
        // $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
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

    public function get_username()
    {
        if ($this->user_type === "trainer") {
            $trainerModel = new Trainer();
            $this->user_name = $trainerModel->get_username_by_id($this->user_id);
        } 
        
        if ($this->user_type === "rat") {
            $customerModel = new Customer();
            $this->user_name = $customerModel->get_username_by_id($this->user_id);
        }
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (type, description, user_id, user_type) VALUES (:type, :description, :user_id, :user_type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
        ]);
        $this->id = $this->conn->lastInsertId();
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

    public function has_unreviewed_complaints(): bool
    {
        $sql = "SELECT COUNT(*) FROM $this->table WHERE review_message IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
