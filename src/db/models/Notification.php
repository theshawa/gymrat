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
    public DateTime $created_at;
    public ?DateTime $valid_till;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? "";
        $this->message = $data['message'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->valid_till = $data['valid_till'] ? new DateTime($data['valid_till']) : null;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (title, message,valid_till) VALUES (:title, :message,:valid_till)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'title' => $this->title,
            'message' => $this->message,
            'valid_till' => $this->valid_till,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
    }

    public function get_all_of_user(int $user_id, string $user_type)
    {
        $sql = "SELECT * FROM $this->table INNER JOIN notification_user ON notifications.id = notification_user.notification_id WHERE 
        notification_user.user_id = :user_id 
        AND notification_user.user_type = :user_type
        AND (valid_till IS NULL OR valid_till >= CURRENT_TIMESTAMP)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);
        $items = $stmt->fetchAll();

        return array_map(function ($item) {
            $notification = new Notification();
            $notification->fill($item);
            return $notification;
        }, $items);
    }
}
