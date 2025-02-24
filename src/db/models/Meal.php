<?php

require_once __DIR__ . "/../Model.php";

class Meal extends Model
{
    protected $table = "meals";

    public int $id;
    public string $name;
    public string $description;
    public string $image;
    public int $calories;
    public int $proteins;
    public int $fats;

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->image = $data['image'] ?? "";
        $this->calories = $data['calories'] ?? 0;
        $this->proteins = $data['proteins'] ?? 0;
        $this->fats = $data['fats'] ?? 0;
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
        $sql = "INSERT INTO $this->table (name, description, image, calories, proteins, fats) VALUES (:name, :description, :image, :calories, :proteins, :fats)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'calories' => $this->calories,
            'proteins' => $this->proteins,
            'fats' => $this->fats,
        ]);
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name = :name, description = :description, image = :image, calories = :calories, proteins = :proteins, fats = :fats, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'calories' => $this->calories,
            'proteins' => $this->proteins,
            'fats' => $this->fats,
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
