<?php

require_once __DIR__ . "/../Model.php";

class Complaint extends Model
{
    protected $table = "complaints";

public int $id;
public string $type;
public string $description;
public int $user_id;
public DateTime $created_at;
public int $is_created_by_trainer;



public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['type'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->user_id = $data['user_id'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? null);
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
            $complaint->fill(
                [
                    'id' => $item['id'],
                    'type' => $item['type'],
                    'description' => $item['description'],
                    'user_id' => $item['user_id'],
                    'created_at' => $item['created_at'],
                    'is_created_by_trainer' => $item['is_created_by_trainer']
                ]
            );
            return $complaint;
        }, $items);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (id, type, description, user_id, created_at, is_created_by_trainer) VALUES (:id, :type, :description, :user_id, :created_at, :is_created_by_trainer)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'is_created_by_trainer' => $this->is_created_by_trainer

        ]);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

}