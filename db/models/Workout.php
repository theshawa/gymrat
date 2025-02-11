<?php

require_once __DIR__ . "/../Model.php";

class Workout extends Model
{
    protected $table = "workouts";

    public int $id;
    public string $name;
    public string $description;
    public int $duration;

    public DateTime $created_at;
    public DateTime $updated_at;

    public array $exercises = [];


    public function __construct()
    {
        parent::__construct();
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();

        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->duration = $data['duration'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? null);
        $this->exercises = $data['exercises'] ?? [];
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

    public function __sleep()
    {
        // Specify the properties to be serialized
        return ['id', 'name', 'description', 'duration', 'created_at', 'updated_at', 'exercises'];
    }

    public function __wakeup()
    {
        // Reinitialize the PDO instance upon unserialization
        $this->conn = Database::get_conn();
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $workout = new Workout();
            $workout->fill(
                [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'duration' => $item['duration'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'exercises' => $this->get_exercises($item['id'])
                ]
            );
            return $workout;
        }, $items);
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

    public function get_by_id(int $id = null)
    {
        $id = $id ?? $this->id;
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            $data['exercises'] = $this->get_exercises($id);
            $this->fill($data);
        }
    }

    private function get_exercises(int $workout_id): array
    {
        $sql = "SELECT * FROM workout_exercises WHERE workout_id = :workout_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['workout_id' => $workout_id]);
        $exercises = $stmt->fetchAll();

        return array_map(function($exercise) {
            $exercise['isUpdated'] = false;
            $exercise['isDeleted'] = false;
            return $exercise;
        }, $exercises);
    }
}
