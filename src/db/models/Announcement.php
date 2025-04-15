<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/Trainer.php";
require_once __DIR__ . "/Customer.php";

class Announcement extends Model
{
    protected $table = "announcements";

    public int $id;
    public string $title;
    public string $message;
    public string $to_all;
    public string $source;
    public DateTime $created_at;
    public DateTime $valid_till;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? "";
        $this->message = $data['message'] ?? "";
        $this->to_all = $data['to_all'] ?? "";
        $this->source = $data['source'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->valid_till = new DateTime($data['valid_till'] ?? '');
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (title, message, to_all, source, valid_till) VALUES (:title, :message, :to_all, :source, :valid_till)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'title' => $this->title,
            'message' => $this->message,
            'to_all' => $this->to_all,
            'source' => $this->source,
            'valid_till' => $this->valid_till->format('Y-m-d H:i:s'),
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function get_all_of_source(string $source)
    {
        $sql = "SELECT * FROM $this->table WHERE source = :source ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['source' => $source]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $announcement = new Announcement();
            $announcement->fill($item);
            return $announcement;
        }, $items);
    }

    public function get_all_of_user(string $user_type, int $user_id = 0)
    {
        try {
            if ($user_type == "rat") {
                // Use a simplified query that doesn't rely on the trainer field
                $sql = "SELECT * FROM $this->table 
                        WHERE (to_all = 'rats' OR to_all = 'rats+trainers') 
                        AND valid_till >= NOW()
                        ORDER BY created_at DESC";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            } else if ($user_type == "trainer") {
                $sql = "SELECT * FROM $this->table 
                        WHERE (to_all = 'trainers' OR to_all = 'rats+trainers') 
                        AND valid_till >= NOW()
                        ORDER BY created_at DESC";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            } else {
                throw new Exception("Invalid user type");
            }

            $items = $stmt->fetchAll();
            return array_map(function ($item) {
                $announcement = new Announcement();
                $announcement->fill($item);
                return $announcement;
            }, $items);
        } catch (Exception $e) {
            // Return an empty array if there's an error
            error_log("Error getting announcements: " . $e->getMessage());
            return [];
        }
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $item = $stmt->fetch();
        if (!$item) {
            throw new Exception("Announcement not found");
        }
        $this->fill($item);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
    }

    public function delete_all_of_source(string $source)
    {
        $sql = "DELETE FROM $this->table WHERE source = :source";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'source' => $source,
        ]);
    }
}