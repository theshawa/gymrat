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

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(?int $id, string $name = "", string $description = "", float $price = 1, int $duration = 1, int $is_locked = 0)
    {
        $this->id = is_null($id) ? 0 : $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->duration = $duration;
        $this->is_locked = $is_locked;
    }


    public function get_all(): array
    {
        try {
            $sql = "SELECT * FROM $this->table";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $items = $stmt->fetchAll();
            return array_map(function ($item) {
                $membershipPlan = new MembershipPlan();
                $membershipPlan->fill($item['id'], $item['name'], $item['description'], $item['price'], $item['duration'], $item['is_locked']);
                return $membershipPlan;
            }, $items);
        } catch (PDOException $e) {
            die("error fetching membership plans: " . $e->getMessage());
        }
    }

    public function get_by_id(int $id)
    {
        try {
            $sql = "SELECT * FROM $this->table WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $item = $stmt->fetch();
            if (!$item) {
                die("membership plan not found");
            }
            $this->fill($item['id'], $item['name'], $item['description'], $item['price'], $item['duration'], $item['is_locked']);
        } catch (PDOException $e) {
            die("error fetching membership plan: " . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $sql = "INSERT INTO $this->table (name, description, price, duration, is_locked) VALUES (:name, :description, :price, :duration, :is_locked)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
                'is_locked' => $this->is_locked
            ]);
            $this->id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("error creating membership plan: " . $e->getMessage());
        }
    }


    public function update()
    {
        try {
            $sql = "UPDATE $this->table SET name = :name, description = :description, price = :price, duration = :duration, is_locked = :is_locked WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
                'id' => $this->id,
                'is_locked' => $this->is_locked
            ]);
        } catch (PDOException $e) {
            die("error updating membership plan: " . $e->getMessage());
        }
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
        try {
            $sql = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $this->id]);
        } catch (PDOException $e) {
            die("error deleting membership plan: " . $e->getMessage());
        }
    }
}
