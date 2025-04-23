<?php

require_once __DIR__ . "/../Model.php";

class Meal extends Model
{
    protected $table = "meals";

    public int $id;
    public string $name;
    public string $description;
    public string $image;
    public float $calories;
    public float $proteins;
    public float $fats;
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
        return ['id', 'name', 'description', 'created_at', 'updated_at', 'image', 'calories', 'proteins', 'fats'];
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
        $this->image = $data['image'] ?? "";
        $this->calories = isset($data['calories']) ? (float)$data['calories'] : 0.0; 
        $this->proteins = isset($data['proteins']) ? (float)$data['proteins'] : 0.0; 
        $this->fats = isset($data['fats']) ? (float)$data['fats'] : 0.0;
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
            $meal = new Meal();
            $meal->fill($item);
            return $meal;
        }, $items);
    }

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) {
            die("Meal not found");
        }
        $this->fill($item);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, image, calories, proteins, fats, measure_unit, created_at) VALUES (:name, :description, :image, :calories, :proteins, :fats,:measure_unit, CURRENT_TIMESTAMP)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'calories' => $this->calories,
            'proteins' => $this->proteins,
            'fats' => $this->fats,
            'measure_unit' => $this->measure_unit,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name=:name, description=:description, image=:image, calories=:calories, proteins=:proteins, fats=:fats, measure_unit=:measure_unit, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'calories' => $this->calories,
            'proteins' => $this->proteins,
            'fats' => $this->fats,
            'measure_unit' => $this->measure_unit,
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

    function addMealTitles(array $meals): array
    {
        $mealModel = new Meal();
        $mealTitles = $mealModel->get_all_titles();

        foreach ($meals as &$meal) {
            if (isset($meal['meal_id'])) {
                $meal['title'] = $mealTitles[$meal['meal_id']] ?? 'Unknown Meal';
            }
        }

        return $meals;
    }
}
