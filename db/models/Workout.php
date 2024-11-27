<?php

require_once __DIR__ . "/../Model.php";

class Staff extends Model
{
    protected $table = "workouts";

    public int $id;
    public string $name;
    public string $description;
    public int $duration;

    public DateTime $created_at;
    public DateTime $updated_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->duration = $data['duration'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? null);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, duration) VALUES (:name, :description, :duration)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
        ]);
        $this->id = $this->conn->lastInsertId();
    }


    public function update()
    {
        $sql = "UPDATE $this->table SET name=:name, description=:description, duration=:duration, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
        ]);
    }

    public function save()
    {
        if ($this->id === 0) {
            $this->create();
        } else {
            $this->update();
        }
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->fill($data);
        }
    }
}
