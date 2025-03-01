<?php

require_once __DIR__ . "/../Model.php";

class MealPlanMeal extends Model
{
    protected $table = "mealplan_meals";

    public int $id;
    public int $mealplan_id;
    public int $meal_id;
    public string $day;
    public string $time;
    public int $amount;
    public bool $isUpdated = false;
    public bool $isDeleted = false;

    public function __construct(array $data = [])
    {
        parent::__construct();
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->mealplan_id = $data['mealplan_id'] ?? 0;
        $this->meal_id = $data['meal_id'] ?? 0;
        $this->day = $data['day'] ?? "";
        $this->time = $data['time'] ?? "";
        $this->amount = $data['amount'] ?? 0;
        $this->isUpdated = $data['isUpdated'] ?? false;
        $this->isDeleted = $data['isDeleted'] ?? false;
    }

    public function get_by_mealplan_id(int $mealplan_id): array
    {
        $sql = "SELECT * FROM $this->table WHERE mealplan_id = :mealplan_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mealplan_id' => $mealplan_id]);
        $meals = $stmt->fetchAll();

        return array_map(function($meal) {
            return new MealPlanMeal($meal);
        }, $meals);
    }

    public function save()
    {
        if ($this->isDeleted) {
            $this->delete();
        } elseif ($this->isUpdated) {
            if ($this->id == 0) {
                $this->create();
            } else {
                $this->update();
            }
        }
    }

    private function create()
    {
        $sql = "INSERT INTO $this->table (mealplan_id, meal_id, day, time, amount) VALUES (:mealplan_id, :meal_id, :day, :time, :amount)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'mealplan_id' => $this->mealplan_id,
            'meal_id' => $this->meal_id,
            'day' => $this->day,
            'time' => $this->time,
            'amount' => $this->amount,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    private function update()
    {
        $sql = "UPDATE $this->table SET meal_id=:meal_id, day=:day, time=:time, amount=:amount WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'meal_id' => $this->meal_id,
            'day' => $this->day,
            'time' => $this->time,
            'amount' => $this->amount,
        ]);
    }

    private function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }
}
