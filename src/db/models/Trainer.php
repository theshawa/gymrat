<?php

// db/models/Trainer.php

require_once __DIR__ . "/../Model.php";

class Trainer extends Model
{
    protected $table = "trainers";

    public int $id = 0;
    public string $fname = "";
    public string $lname = "";
    public string $username = "";
    public string $password = "";
    public null|array|string $avatar = null;
    public string $bio = "";
    public float $rating = 0;
    public int $review_count = 0;

    public function fill(array $data)
    {
        $this->id = isset($data['id']) ? (int) $data['id'] : 0;
        $this->fname = $data['fname'] ?? "";
        $this->lname = $data['lname'] ?? "";
        $this->username = $data['username'] ?? "";
        $this->password = $data['password'] ?? "";
        $this->avatar = $data['avatar'] ?? null;
        $this->bio = $data['bio'] ?? "";
        $this->rating = isset($data['rating']) ? (float) $data['rating'] : 0.0;
        $this->review_count = isset($data['review_count']) ? (int) $data['review_count'] : 0;
    }

    public function save()
    {
        if ($this->exists()) {
            $this->update();
        } else {
            $this->create();
        }
    }

    protected function create()
    {
        $sql = "INSERT INTO $this->table (
            fname,lname, username, password, bio, avatar, rating, review_count
        ) VALUES (
            :fname, :lname, :username, :password, :bio, :avatar, :rating, :review_count
        )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'username' => $this->username,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'rating' => $this->rating,
            'review_count' => $this->review_count
        ]);

        $this->id = $this->conn->lastInsertId(); // Set ID for future updates
    }

    protected function update()
    {
        $sql = "UPDATE $this->table SET 
            fname = :fname,
            lname = :lname,
            bio = :bio,
            avatar = :avatar,
            rating = :rating,
            review_count = :review_count
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'rating' => $this->rating,
            'review_count' => $this->review_count
        ]);
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $data = $stmt->fetch();

        if ($data) {
            $this->fill($data);
            return true; // ✅ Return true when a record is found
        }
        return false; // ✅ Return false if no record is found
    }

    public function get_by_username()
    {
        $sql = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $this->username]);
        $data = $stmt->fetch();

        if ($data) {
            $this->fill($data);
            return true;
        }
        return false;
    }

    public function exists()
    {
        $sql = "SELECT id FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetchColumn() ? true : false;
    }
}