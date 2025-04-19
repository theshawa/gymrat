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
    public string $measure_unit;
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
        $this->image = $data['image'] ?? "";
        $this->calories = $data['calories'] ?? 0;
        $this->proteins = $data['proteins'] ?? 0;
        $this->fats = $data['fats'] ?? 0;
        $this->measure_unit = $data['measure_unit'] ?? "g";
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

    function get_measure_unit_single(): string
    {
        $titles = [
            'g' => 'gram',
            'kg' => 'kilogram',
            'oz' => 'ounce',
            'lb' => 'pound',
            'ml' => 'milliliter',
            'l' => 'liter',
            'fl oz' => 'fluid ounce',
            'cup' => 'cup',
            'tbsp' => 'tablespoon',
            'tsp' => 'teaspoon',
            'piece' => 'piece',
            'slice' => 'slice',
            'can' => 'can',
            'scoop' => 'scoop',
            'serving' => 'serving',
            'pack' => 'pack',
            'bottle' => 'bottle'

        ];
        return $titles[$this->measure_unit] ?? $this->measure_unit;
    }

    function get_measure_unit_plural(): string
    {
        $titles = [
            'g' => 'grams',
            'kg' => 'kilograms',
            'oz' => 'ounces',
            'lb' => 'pounds',
            'ml' => 'milliliters',
            'l' => 'liters',
            'fl oz' => 'fluid ounces',
            'cup' => 'cups',
            'tbsp' => 'tablespoons',
            'tsp' => 'teaspoons',
            'piece' => 'pieces',
            'slice' => 'slices',
            'can' => 'cans',
            'scoop' => 'scoops',
            'serving' => 'servings',
            'pack' => 'packs',
            'bottle' => 'bottles'
        ];
        return $titles[$this->measure_unit] ?? $this->measure_unit;
    }

    function get_default_amount()
    {
        $default_amounts = [
            'g' => 100,
            'kg' => 1,
            'oz' => 3.5,
            'lb' => 2.2,
            'ml' => 100,
            'l' => 1,
            'fl oz' => 3.4,
            'cup' => 1,
            'tbsp' => 1,
            'tsp' => 1,
            'piece' => 1,
            'slice' => 1,
            'can' => 1,
            'scoop' => 1,
            'serving' => 1,
            'pack' => 1,
            'bottle' => 1
        ];
        return $default_amounts[$this->measure_unit] ?? 0;
    }
}
