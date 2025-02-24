<?php

require_once __DIR__ . "/../Model.php";

class WorkoutExercise extends Model
{
    protected $table = "workout_exercises";

    public int $id;
    public int $workout_id;
    public int $exercise_id;
    public int $sets;
    public int $reps;
    public bool $isUpdated = false;
    public bool $isDeleted = false;

    public function __construct(array $data = [])
    {
        parent::__construct();
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->workout_id = $data['workout_id'] ?? 0;
        $this->exercise_id = $data['exercise_id'] ?? 0;
        $this->sets = $data['sets'] ?? 0;
        $this->reps = $data['reps'] ?? 0;
        $this->isUpdated = $data['isUpdated'] ?? false;
        $this->isDeleted = $data['isDeleted'] ?? false;
    }

    public function get_by_workout_id(int $workout_id): array
    {
        $sql = "SELECT * FROM $this->table WHERE workout_id = :workout_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['workout_id' => $workout_id]);
        $exercises = $stmt->fetchAll();

        return array_map(function($exercise) {
            return new WorkoutExercise($exercise);
        }, $exercises);
    }

    public function save()
    {
        // This function doesnt save the object if isUpdated is not true
        if ($this->isDeleted) {
            $sql = "DELETE FROM $this->table WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $this->id]);
        } elseif ($this->isUpdated) {
            if ($this->id < 0) {
                $sql = "INSERT INTO $this->table (workout_id, exercise_id, sets, reps) VALUES (:workout_id, :exercise_id, :sets, :reps)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'workout_id' => $this->workout_id,
                    'exercise_id' => $this->exercise_id,
                    'sets' => $this->sets,
                    'reps' => $this->reps,
                ]);
                $this->id = $this->conn->lastInsertId();
            } else {
                $sql = "UPDATE $this->table SET exercise_id=:exercise_id, sets=:sets, reps=:reps WHERE id=:id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'id' => $this->id,
                    'exercise_id' => $this->exercise_id,
                    'sets' => $this->sets,
                    'reps' => $this->reps,
                ]);
            }
        } 
    }
}
