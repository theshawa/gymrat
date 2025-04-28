<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/WorkoutExercise.php";

class Workout extends Model
{
    protected $table = "workouts";

    public int $id = 0; // Initialize with default value to avoid the error
    public string $name = "";
    public string $description = "";
    public int $duration = 0;

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
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? '');
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
        $this->id = (int) $this->conn->lastInsertId();
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

    public function get_all_titles(): array
    {
        $sql = "SELECT id, name FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        $titles = [];
        foreach ($items as $item) {
            $titles[$item['id']] = $item['name'];
        }
        return $titles;
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

        if (!empty($this->exercises)) {
            foreach ($this->exercises as $exerciseData) {
                $exercise = new WorkoutExercise($exerciseData);
                $exercise->workout_id = $this->id; // Ensure workout_id is set correctly
                $exercise->save();
            }
        }
    }

    public function save()
    {
        if ($this->id === 0) {
            // Create new workout record
            $this->create();

            // Now update exercise relations with the new workout ID
            if (!empty($this->exercises)) {
                foreach ($this->exercises as &$exerciseData) {
                    $exerciseData['workout_id'] = $this->id;
                    $exercise = new WorkoutExercise($exerciseData);
                    $exercise->save();
                }
            }
        } else {
            $this->update();
        }
    }


    public function delete()
    {
        if (!empty($this->exercises)) {
            foreach ($this->exercises as $exerciseData) {
                $exercise = new WorkoutExercise($exerciseData);
                $exercise->isDeleted = true;
                $exercise->save();
            }
        }

        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }


    public function get_by_id(int $id = 0)
    {
        $id = $id > 0 ? $id : $this->id;
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
        $workoutExercise = new WorkoutExercise();
        $exercises = $workoutExercise->get_by_workout_id($workout_id);
        return array_map(function ($exercise) {
            return [
                'id' => $exercise->id,
                'edit_id' => $exercise->id,
                'workout_id' => $exercise->workout_id,
                'exercise_id' => $exercise->exercise_id,
                'sets' => $exercise->sets,
                'day' => $exercise->day,
                'reps' => $exercise->reps,
                'isUpdated' => $exercise->isUpdated,
                'isDeleted' => $exercise->isDeleted,
            ];
        }, $exercises);
    }

    public function get_total_count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
}