<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/Trainer.php";
require_once __DIR__ . "/Customer.php";

class Notification extends Model
{
    protected $table = "notifications";

    public int $id;
    public string $title;
    public string $message;
    public int $receiver_id;
    public string $receiver_type;
    public string $source;
    public bool $is_read = false;
    public DateTime $created_at;
    public ?DateTime $valid_till;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? "";
        $this->message = $data['message'] ?? "";
        $this->receiver_id = $data['receiver_id'] ?? 0;
        $this->receiver_type = $data['receiver_type'] ?? "rat";
        $this->source = $data['source'] ?? "system";
        $this->is_read = $data['is_read'] ?? false;
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->valid_till = isset($data['valid_till']) ? new DateTime($data['valid_till']) : null;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (title, message, receiver_id, receiver_type, source, valid_till) VALUES (:title, :message, :receiver_id, :receiver_type, :source, :valid_till)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'title' => $this->title,
            'message' => $this->message,
            'receiver_id' => $this->receiver_id,
            'receiver_type' => $this->receiver_type,
            'source' => $this->source,
            'valid_till' => $this->valid_till ? $this->valid_till->format('Y-m-d H:i:s') : null,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function get_all_of_user(int $user_id, string $user_type)
    {
        $sql = "SELECT * FROM $this->table WHERE receiver_id = :receiver_id AND receiver_type = :receiver_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'receiver_id' => $user_id,
            'receiver_type' => $user_type,
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $notification = new Notification();
            $notification->fill($item);
            return $notification;
        }, $items);
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $item = $stmt->fetch();
        if (!$item) {
            throw new Exception("Notification not found");
        }
        $this->fill($item);
    }

    public function mark_as_read()
    {
        $sql = "UPDATE $this->table SET is_read = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
    }

    public function delete_all_of_user(int $user_id, string $user_type)
    {
        $sql = "DELETE FROM $this->table WHERE receiver_id = :receiver_id AND receiver_type = :receiver_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'receiver_id' => $user_id,
            'receiver_type' => $user_type,
        ]);
    }
}
