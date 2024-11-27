<?php

require_once __DIR__ . "/../Model.php";

class Staff extends Model
{
    protected $table = "staff";

    public int $id;
    public string $name;
    public string $email;
    public ?string $password;
    public string $role;

    public DateTime $created_at;
    public DateTime $updated_at;


    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? "";
        $this->email = $data['email'] ?? "";
        $this->password = $data['password'] ?? "";
        $this->role = $data['role'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? null);
    }

    public function create()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO $this->table (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
        ]);
        $this->id = $this->conn->lastInsertId();
    }


    public function update()
    {
        $sql = "UPDATE $this->table SET name=:name, email=:email, password=:password, role=:role, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
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

    public function get_by_email()
    {
        $sql = "SELECT * FROM $this->table WHERE email=:email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $this->email]);
        $data = $stmt->fetch();
        if ($data) {
            $this->fill($data);
        }
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->fill($data);
        }
    }

    public function update_password()
    {
        $field = $this->id ? 'id' : ($this->email ? "email" : null);
        if (!$field) {
            throw new PDOException("Id or email is required to update password");
        }
        $sql = "UPDATE $this->table SET password=:password WHERE $field=:$field";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $field => $this->$field,
            'password' => $this->password
        ]);
    }
}
