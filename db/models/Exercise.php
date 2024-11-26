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
    public DateTime $created_at;
    public DateTime $updated_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->video_link = $data['video_link'] ?? "";
        $this->image = $data['image'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? null);
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
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at']
            ]
        );
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, video_link, image) VALUES (:name, :description, :video_link, :image)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'video_link' => $this->video_link,
            'image' => $this->image,
        ]);
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name = :name, description = :description, video_link = :video_link, image = :image, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'video_link' => $this->video_link,
            'image' => $this->image,
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
}
