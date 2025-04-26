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
    public string $status;
    public int $quantity;
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
        $this->status = $data['status'] ?? "available";
        $this->quantity = $data['quantity'] ?? 0;
        $this->purchase_date = new DateTime($data['purchase_date'] ?? '');
        $this->last_maintenance = new DateTime($data['last_maintenance'] ?? '');
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? '');
    }

    public function __sleep()
    {
        return array_diff(array_keys(get_object_vars($this)), ['conn']);
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $equipment = new Equipment();
            $equipment->fill($item);
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
        $this->fill($item);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, type, manufacturer, description, image, purchase_date, last_maintenance) VALUES (:name, :type, :manufacturer, :description, :image, :purchase_date, :last_maintenance)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'type' => $this->type,
            'manufacturer' => $this->manufacturer,
            'description' => $this->description,
            'image' => $this->image,
            'purchase_date' => $this->purchase_date->format('Y-m-d H:i:s'),
            'last_maintenance' => $this->last_maintenance->format('Y-m-d H:i:s'),
        ]);
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name = :name, type = :type, manufacturer = :manufacturer, description = :description, image = :image, purchase_date = :purchase_date, last_maintenance = :last_maintenance, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'manufacturer' => $this->manufacturer,
            'description' => $this->description,
            'image' => $this->image,
            'purchase_date' => $this->purchase_date->format('Y-m-d H:i:s'),
            'last_maintenance' => $this->last_maintenance->format('Y-m-d H:i:s'),
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
