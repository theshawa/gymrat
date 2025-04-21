<?php

require_once __DIR__ . "/../Model.php";

class Settings extends Model
{
    protected $table = "settings";

    public int $id;
    public string $contact_email;
    public int $contact_phone;
    public int $workout_session_expiry;
    public int $rat_seats;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->contact_email = $data['contact_email'] ?? "";
        $this->contact_phone = $data['contact_phone'] ?? 0;
        $this->workout_session_expiry = $data['workout_session_expiry'] ?? 0;
        $this->rat_seats = $data['rat_seats'] ?? 0;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM $this->table LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data) {
            $this->fill($data);
        }
    }

    public function save()
    {
        // since only one row exists
        $sql = "UPDATE $this->table SET 
        contact_email = :contact_email, 
        contact_phone = :contact_phone, 
        workout_session_expiry = :workout_session_expiry,
        rat_seats = :rat_seats
        WHERE id = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'workout_session_expiry' => $this->workout_session_expiry,
            'rat_seats' => $this->rat_seats,
        ]);
    }

    public function __sleep()
    {
        return ['id', 'contact_email', 'contact_phone', 'workout_session_expiry'];
    }

    public function __wakeup()
    {
        // Reinitialize the database connection if necessary
        $this->conn = Database::get_conn();
    }
}
