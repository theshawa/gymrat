<?php

require_once __DIR__ . "/../Model.php";

class MembershipPlan extends Model
{
    protected $table = "membership_plans";

    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public int $duration;
    public int $is_locked;
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
        $this->price = $data['price'] ?? 1;
        $this->duration = $data['duration'] ?? 1;
        $this->is_locked = $data['is_locked'] ?? 0;
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
            $membershipPlan = new MembershipPlan();
            $membershipPlan->fill($item);
            return $membershipPlan;
        }, $items);
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("membership plan not found");
        }
        $this->fill($item);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, price, duration, is_locked) VALUES (:name, :description, :price, :duration, :is_locked)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration,
            'is_locked' => $this->is_locked
        ]);
    }


    public function update()
    {
        $sql = "UPDATE $this->table SET name = :name, description = :description, price = :price, duration = :duration, is_locked = :is_locked, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration,
            'id' => $this->id,
            'is_locked' => $this->is_locked
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

    public function __sleep()
    {
        return ['id', 'name', 'description', 'price', 'duration', 'is_locked', 'created_at', 'updated_at'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }
}
