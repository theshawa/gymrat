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

    public function __construct()
    {
        parent::__construct();
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->trainerId = $data['trainer_id'] ?? 0;
        $this->description = $data['description'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? '');
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $workoutRequest = new WorkoutRequest();
            $workoutRequest->fill($item);
            return $workoutRequest;
        }, $items);
    }
}
