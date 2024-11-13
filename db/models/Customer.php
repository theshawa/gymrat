<?php

require_once __DIR__ . "/../Model.php";

class Customer extends Model
{
    protected $table = "customers";

    public int $id;
    public string $fname;
    public string $lname;
    public string $email;
    public ?string $password;
    public string $phone;
    public null | array| string $avatar;
    public DateTime $created_at;
    public DateTime $updated_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->fname = $data['fname'] ?? "";
        $this->lname = $data['lname'] ?? "";
        $this->email = $data['email'] ?? "";
        $this->password = $data['password'] ?? "";
        $this->phone = $data['phone'] ?? "";
        $this->avatar = $data['avatar'] ?? null;
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->updated_at = new DateTime($data['updated_at'] ?? $data['created_at'] ?? null);
    }

    public function create()
    {
        try {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO $this->table (fname,lname, email, phone, avatar,password) VALUES (:fname, :lname, :email, :phone, :avatar, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'fname' => $this->fname,
                'lname' => $this->lname,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->avatar,
                'password' => $this->password
            ]);
            $this->id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("[database] error creating customer: " . $e->getMessage());
        }
    }


    public function update()
    {
        try {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = "UPDATE $this->table SET fname=:fname, lname=:lname, email=:email, phone=:phone, avatar=:avatar, password=:password WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
                'fname' => $this->fname,
                'lname' => $this->lname,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->avatar,
                'password' => $this->password
            ]);
        } catch (PDOException $e) {
            die("[database] error updating customer: " . $e->getMessage());
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

    public function get_by_email(string $email): Customer
    {
        try {
            $sql = "SELECT * FROM $this->table WHERE email=:email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $data = $stmt->fetch();
            if ($data) {
                $this->fill($data);
            }
        } catch (PDOException $e) {
            die("[database] error fetching customer: " . $e->getMessage());
        }
        return $this;
    }
}
