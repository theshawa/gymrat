<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/Trainer.php";
require_once __DIR__ . "/Customer.php";

class NotificationUser extends Model
{
    protected $table = "notification_user";

    public int $user_id;
    public string $notification_id;
    public string $user_type;
    public bool $is_read;

    public function fill(array $data)
    {
        $this->user_id = $data['user_id'] ?? 0;
        $this->notification_id = $data['notification_id'] ?? "";
        $this->user_type = $data['user_type'] ?? "";
        $this->is_read = $data['is_read'] ?? false;
    }

    public function get_all_of_notification(int $notification_id)
    {
        $sql = "SELECT * FROM $this->table WHERE notification_id = :notification_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'notification_id' => $notification_id,
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $notification_user = new NotificationUser();
            $notification_user->fill($item);
            return $notification_user;
        }, $items);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (user_id, notification_id, user_type, is_read) VALUES (:user_id, :notification_id, :user_type, :is_read)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $this->user_id,
            'notification_id' => $this->notification_id,
            'user_type' => $this->user_type,
            'is_read' => $this->is_read,
        ]);
    }

    public function bulk_create(array $notifications)
    {
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO $this->table (user_id, notification_id, user_type, is_read) VALUES (:user_id, :notification_id, :user_type, :is_read)";
            $stmt = $this->conn->prepare($sql);
            foreach ($notifications as $notification) {
                $stmt->execute([
                    'user_id' => $notification->user_id,
                    'notification_id' => $notification->notification_id,
                    'user_type' => $notification->user_type,
                    'is_read' => false,
                ]);
            }
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE user_id = :user_id AND notification_id = :notification_id AND user_type = :user_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $this->user_id,
            'notification_id' => $this->notification_id,
            'user_type' => $this->user_type,
        ]);
    }

    public function mark_as_read(int $id)
    {
        $sql = "UPDATE $this->table SET is_read = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
    }

    public function delete_all_of_user(int $user_id, string $user_type)
    {
        // get related notifications
        $sql = "SELECT DISTINCT notification_id FROM $this->table WHERE user_id = :user_id AND user_type = :user_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);
        $related_notifications = $stmt->fetchAll();

        // delete notification_records
        $sql = "DELETE FROM $this->table WHERE user_id = :user_id AND user_type = :user_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);

        // delete each notification record when there are no related notification_user records
        foreach ($related_notifications as $notification_record) {
            $notification_users = new NotificationUser();
            $user_records = $notification_users->get_all_of_notification($notification_record['notification_id']);
            if (count($user_records) == 0) {
                $notification = new Notification();
                $notification->fill([
                    'id' => $notification_record['notification_id']
                ]);
                $notification->delete();
            }
        }
    }
}
