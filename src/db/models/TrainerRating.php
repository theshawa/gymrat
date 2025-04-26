<?php

require_once __DIR__ . "/../Model.php";

class TrainerRating extends Model
{
    protected $table = "trainer_ratings";

    public int $id;
    public int $trainer_id;
    public int $customer_id;
    public int $rating;
    public string $review;
    public DateTime $created_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->trainer_id = $data['trainer_id'] ?? 0;
        $this->customer_id = $data['customer_id'] ?? 0;
        $this->rating = $data['rating'] ?? 0;
        $this->review = $data['review'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function get_all_of_trainer(int $trainer_id): array
    {
        $sql = "SELECT * FROM $this->table WHERE trainer_id = :trainer_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'trainer_id' => $trainer_id
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $rating = new TrainerRating();
            $rating->fill($item);
            return $rating;
        }, $items);
    }

    public function delete_all_of_trainer(int $trainer_id)
    {
        $sql = "DELETE FROM $this->table WHERE trainer_id = :trainer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'trainer_id' => $trainer_id
        ]);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (trainer_id, customer_id, rating, review) VALUES (:trainer_id, :customer_id, :rating, :review)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'trainer_id' => $this->trainer_id,
            'customer_id' => $this->customer_id,
            'rating' => $this->rating,
            'review' => $this->review,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id
        ]);
    }

    public function get_all_of_user(int $user_id)
    {
        $sql = "SELECT * FROM $this->table WHERE customer_id = :customer_id OR trainer_id = :trainer_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $user_id,
            'trainer_id' => $user_id
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $rating = new TrainerRating();
            $rating->fill($item);
            return $rating;
        }, $items);
    }

    public function get_rating_of_trainer(int $trainer_id)
    {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as review_count FROM $this->table WHERE trainer_id = :trainer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'trainer_id' => $trainer_id
        ]);
        $result = $stmt->fetch();
        return [
            'avg_rating' => $result['avg_rating'] ?? 0,
            'review_count' => $result['review_count'] ?? 0
        ];
    }
}
