<?php

require_once __DIR__ . "/../Model.php";

class Exercise extends Model
{
    protected $table = "exercises";

    public int $id;
    public string $name;
    public string $description;
    public string $video_link;
    public string $image;
    public string $muscle_group;
    public string $difficulty_level;
    public string $type;
    public string $equipment_needed;
    public DateTime $created_at;
    public DateTime $updated_at;

    public function __construct()
    {
        parent::__construct();
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function __sleep()
    {
        return ['id', 'name', 'description', 'video_link', 'created_at', 'updated_at', 'image', 'muscle_group', 'difficulty_level', 'type', 'equipment_needed'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }


    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->video_link = $data['video_link'] ?? "";
        $this->image = $data['image'] ?? "";
        $this->muscle_group = $data['muscle_group'] ?? "";
        $this->difficulty_level = $data['difficulty_level'] ?? "";
        $this->type = $data['type'] ?? "";
        $this->equipment_needed = $data['equipment_needed'] ?? "";
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
            $exercise = new Exercise();
            $exercise->fill(
                [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'video_link' => $item['video_link'],
                    'image' => $item['image'],
                    'muscle_group' => $item['muscle_group'],
                    'difficulty_level' => $item['difficulty_level'],
                    'type' => $item['type'],
                    'equipment_needed' => $item['equipment_needed'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at']
                ]
            );
            return $exercise;
        }, $items);
    }

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("Exercise not found");
        }
        $this->fill(
            [
                'id' => $item['id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'video_link' => $item['video_link'],
                'image' => $item['image'],
                'muscle_group' => $item['muscle_group'],
                'difficulty_level' => $item['difficulty_level'],
                'type' => $item['type'],
                'equipment_needed' => $item['equipment_needed'],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at']
            ]
        );
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, video_link, image, muscle_group, difficulty_level, type, equipment_needed) VALUES (:name, :description, :video_link, :image, :muscle_group, :difficulty_level, :type, :equipment_needed)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'video_link' => $this->video_link,
            'image' => $this->image,
            'muscle_group' => $this->muscle_group,
            'difficulty_level' => $this->difficulty_level,
            'type' => $this->type,
            'equipment_needed' => $this->equipment_needed,
        ]);
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name = :name, description = :description, video_link = :video_link, image = :image, muscle_group = :muscle_group, difficulty_level = :difficulty_level, type = :type, equipment_needed = :equipment_needed, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'video_link' => $this->video_link,
            'image' => $this->image,
            'muscle_group' => $this->muscle_group,
            'difficulty_level' => $this->difficulty_level,
            'type' => $this->type,
            'equipment_needed' => $this->equipment_needed,
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

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
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

    function addExerciseTitles(array $exercises): array
    {
        $exerciseModel = new Exercise();
        $exerciseTitles = $exerciseModel->get_all_titles();

        foreach ($exercises as &$exercise) {
            if (isset($exercise['exercise_id'])) {
                $exercise['title'] = $exerciseTitles[$exercise['exercise_id']] ?? 'Unknown Exercise';
            }
        }

        return $exercises;
    }

    public function get_title_by_id(int $id): string
    {
        $sql = "SELECT name FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        if ($result && isset($result['name'])) {
            return $result['name'];
        }

        return 'Title not found';
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
