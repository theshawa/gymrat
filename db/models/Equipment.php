<?php

require_once __DIR__ . "/../Model.php";

class Equipment extends Model
{
    protected $table = "equipments";

    public int $id;
    public string $name;
    public string $type;
    public string $manufacturer;
    public string $description;
    public string $image;
    public DateTime $purchase_date;
    public DateTime $last_maintenance;
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
        $this->type = $data['type'] ?? "";
        $this->manufacturer = $data['manufacturer'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->image = $data['image'] ?? "";
        $this->purchase_date = new DateTime($data['purchase_date'] ?? null);
        $this->last_maintenance = new DateTime($data['last_maintenance'] ?? null);
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
            $equipment = new Equipment();
            $equipment->fill(
                [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'manufacturer' => $item['manufacturer'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'purchase_date' => $item['purchase_date'],
                    'last_maintenance' => $item['last_maintenance'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at']
                ]
            );
            return $equipment;
        }, $items);
    }

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("Equipment not found");
        }
        $this->fill(
            [
                'id' => $item['id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'manufacturer' => $item['manufacturer'],
                'type' => $item['type'],
                'image' => $item['image'],
                'purchase_date' => $item['purchase_date'],
                'last_maintenance' => $item['last_maintenance'],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at']
            ]
        );
    }

//    public function create()
//    {
//        $sql = "INSERT INTO $this->table (name, description, video_link, image) VALUES (:name, :description, :video_link, :image)";
//        $stmt = $this->conn->prepare($sql);
//        $stmt->execute([
//            'name' => $this->name,
//            'description' => $this->description,
//            'video_link' => $this->video_link,
//            'image' => $this->image,
//        ]);
//    }
//
//    public function update()
//    {
//        $sql = "UPDATE $this->table SET name = :name, description = :description, video_link = :video_link, image = :image, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
//        $stmt = $this->conn->prepare($sql);
//        $stmt->execute([
//            'id' => $this->id,
//            'name' => $this->name,
//            'description' => $this->description,
//            'video_link' => $this->video_link,
//            'image' => $this->image,
//        ]);
//    }
//
//    public function save()
//    {
//        if ($this->id === 0) {
//            $this->create();
//        } else {
//            $this->update();
//        }
//    }
//
//    public function delete()
//    {
//        $sql = "DELETE FROM $this->table WHERE id = :id";
//        $stmt = $this->conn->prepare($sql);
//        $stmt->execute(['id' => $this->id]);
//    }
}
