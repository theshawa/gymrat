<?php

require_once __DIR__ . "/../Model.php";

class CustomerEmailVerificationRequest extends Model
{
    protected $table = "customer_email_verification_requests";

    public string $email;
    public string $code;
    public DateTime $created_at;
    public int $creation_attempt;

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(array $data)
    {
        $this->email = $data['email'] ?? "";
        $this->code = $data['code'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? null);
        $this->creation_attempt = $data['creation_attempt'] ?? 1;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (email, code, creation_attempt) VALUES (:email, :code, :creation_attempt)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'email' => $this->email,
            'code' => $this->code,
            'creation_attempt' => $this->creation_attempt
        ]);
    }

    public function get_by_email()
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $this->email]);
        $item = $stmt->fetch();
        if ($item) {
            $this->fill($item);
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $this->email]);
    }
}
