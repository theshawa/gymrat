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
}
