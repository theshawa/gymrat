<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/MealPlanMeal.php";

class MealPlan extends Model
{
    protected $table = "mealplans";

    public int $id;
    public string $name;
    public string $description;
    public int $duration;
    public DateTime $created_at;
    public DateTime $updated_at;
    public array $meals = [];

    public function __construct(array $data = [])
    {
        parent::__construct();
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();

        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->description = $data['description'] ?? "";
        $this->duration = $data['duration'] ?? 0;
        $this->created_at = new DateTime($data['created_at'] ?? '');
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? '');
        $this->meals = $data['meals'] ?? [];
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (name, description, duration) VALUES (:name, :description, :duration)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET name=:name, description=:description, duration=:duration, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
        ]);

        if (!empty($this->meals)) {
            foreach ($this->meals as $mealData) {
                $meal = new MealPlanMeal($mealData);
                $meal->save();
            }
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
        if (!empty($this->meals)) {
            foreach ($this->meals as $mealData) {
                $meal = new MealPlanMeal($mealData);
                $meal->isDeleted = true;
                $meal->save();
            }
        }

        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

    public function get_by_id(int $id = null)
    {
        $id = $id ?? $this->id;
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            $data['meals'] = $this->get_meals($id);
            $this->fill($data);
        }
    }

    private function get_meals(int $mealplan_id): array
    {
        $mealPlanMeal = new MealPlanMeal();
        $meals = $mealPlanMeal->get_by_mealplan_id($mealplan_id);
        return array_map(function($meal) {
            return [
                'id' => $meal->id,
                'mealplan_id' => $meal->mealplan_id,
                'meal_id' => $meal->meal_id,
                'edit_id' => $meal->id, // Not in Database, strickly for backend use
                'day' => $meal->day,
                'time' => $meal->time,
                'amount' => $meal->amount,
                'isUpdated' => $meal->isUpdated,
                'isDeleted' => $meal->isDeleted,
            ];
        }, $meals);
    }

    public function __sleep()
    {
        // Specify the properties to be serialized
        return ['id', 'name', 'description', 'duration', 'created_at', 'updated_at', 'meals'];
    }

    public function __wakeup()
    {
        // Reinitialize the PDO instance upon unserialization
        $this->conn = Database::get_conn();
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $mealPlan = new MealPlan();
            $mealPlan->fill(
                [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'duration' => $item['duration'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'meals' => $this->get_meals($item['id'])
                ]
            );
            return $mealPlan;
        }, $items);
    }
}
