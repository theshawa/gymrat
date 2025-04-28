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
    public ?string $avatar = null; // Changed to proper string type
    public string $bio = "";
    public string $phone = "";

    public function fill(array $data)
    {
        $this->id = isset($data['id']) ? (int) $data['id'] : 0;
        $this->fname = $data['fname'] ?? "";
        $this->lname = $data['lname'] ?? "";
        $this->username = $data['username'] ?? "";
        $this->password = $data['password'] ?? "";
        $this->avatar = $data['avatar'] ?? null;
        $this->bio = $data['bio'] ?? "";
        $this->phone = $data['phone'] ?? "";
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
            fname,lname, username, password, bio, avatar, phone
        ) VALUES (
            :fname, :lname, :username, :password, :bio, :avatar, :phone
        )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'username' => $this->username,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'phone' => $this->phone
        ]);

        $this->id = $this->conn->lastInsertId(); // Set ID for future updates
    }

    protected function update()
    {
        // Add debug log to see what we're trying to update
        error_log("Updating trainer with ID: {$this->id}, avatar: " . var_export($this->avatar, true));

        $sql = "UPDATE $this->table SET 
            fname = :fname,
            lname = :lname,
            bio = :bio,
            avatar = :avatar,
            phone = :phone
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $params = [
            'id' => $this->id,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'phone' => $this->phone
        ];

        error_log("UPDATE params: " . json_encode($params));
        $result = $stmt->execute($params);

        if (!$result) {
            error_log("Database update failed: " . json_encode($stmt->errorInfo()));
        } else {
            error_log("Database update successful, rows affected: " . $stmt->rowCount());
        }
    }

    public function update_password()
    {
        $field = $this->id ? 'id' : ($this->username ? "username" : null);
        if (!$field) {
            throw new PDOException("Id or username is required to update password");
        }
        $sql = "UPDATE $this->table SET password=:password WHERE $field=:$field";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $field => $this->$field,
            'password' => password_hash($this->password, PASSWORD_DEFAULT)
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
            error_log("Retrieved trainer data: " . json_encode($data));
            return true; // Return true when a record is found
        }
        return false; // Return false if no record is found
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

    public function get_username_by_id(int $id): ?string
    {
        $sql = "SELECT username FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        return $data['username'] ?? null;
    }

    public function get_all_trainers(): array
    {
        $sql = "SELECT id, CONCAT(fname, ' ', lname) AS name FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        $trainers = [];
        foreach ($items as $item) {
            $trainers[$item['id']] = $item['name'];
        }
        return $trainers;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $trainers = array_map(function ($trainer) {
            $new_trainer = new Trainer();
            $new_trainer->fill($trainer);
            return $new_trainer;
        }, $data);
        return $trainers;
    }

    public function __sleep()
    {
        return ['id', 'fname', 'lname', 'username', 'password', 'avatar', 'bio', 'phone'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }
}