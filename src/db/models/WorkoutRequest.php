<?php

require_once __DIR__ . "/../Model.php";

class WorkoutRequest extends Model
{
    protected $table = "workout_requests";

    public int $id;
    public int $trainerId;
    public string $description;
    public DateTime $created_at;
    public DateTime $updated_at;
    public int $reviewed; 
    public ?string $trainer; // Only for API

    public function __construct()
    {
        parent::__construct();
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
        $this->reviewed = 0; 
        $this->trainer = null; 
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->trainerId = $data['trainer_id'] ?? 0;
        $this->description = $data['description'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? '');
        $this->reviewed = $data['reviewed'] ?? 0; 
        $this->trainer = null; 
    }

    public function get_all(int $sort = 0, int $filter = 0) 
    {
        $sql = "SELECT * FROM $this->table";
        if ($filter === 1) {
            $sql .= " WHERE reviewed = 0";
        }
        if ($sort === 1) {
            $sql .= " ORDER BY created_at ASC"; 
        } elseif ($sort === -1) {
            $sql .= " ORDER BY created_at DESC"; 
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $workoutRequest = new WorkoutRequest();
            $workoutRequest->fill($item);
            return $workoutRequest;
        }, $items);
    }

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("WorkoutRequest not found");
        }
        $this->fill($item);
    }

    public function confirm_request()
    {
        $sql = "UPDATE $this->table SET reviewed = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

    public function has_unreviewed_requests(): bool
    {
        $sql = "SELECT COUNT(*) FROM $this->table WHERE reviewed = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
