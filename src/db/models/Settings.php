<?php

require_once __DIR__ . "/../Model.php";

class Settings extends Model
{
    protected $table = "settings";

    public int $id;
    public string $contact_email;
    public int $contact_phone;
    public int $workout_session_expiry;
    public int $max_capacity;
    public int $min_workout_time;
    public ?string $gym_banner;
    public ?string $gym_name;
    public ?string $gym_desc;
    public ?string $gym_address;
    public bool $show_widgets;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->contact_email = $data['contact_email'] ?? "";
        $this->contact_phone = $data['contact_phone'] ?? 0;
        $this->workout_session_expiry = $data['workout_session_expiry'] ?? 0;
        $this->max_capacity = $data['max_capacity'] ?? 0;
        $this->min_workout_time = $data['min_workout_time'] ?? 0;
        $this->gym_banner = $data['gym_banner'] ?? null;
        $this->gym_name = $data['gym_name'] ?? null;
        $this->gym_desc = $data['gym_desc'] ?? null;
        $this->gym_address = $data['gym_address'] ?? null;
        $this->show_widgets = isset($data['show_widgets']) ? (bool)$data['show_widgets'] : true;
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
        max_capacity = :max_capacity,
        min_workout_time = :min_workout_time,
        gym_banner = :gym_banner,
        gym_name = :gym_name,
        gym_desc = :gym_desc,
        gym_address = :gym_address,
        show_widgets = :show_widgets
        WHERE id = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'workout_session_expiry' => $this->workout_session_expiry,
            'max_capacity' => $this->max_capacity,
            'min_workout_time' => $this->min_workout_time,
            'gym_banner' => $this->gym_banner,
            'gym_name' => $this->gym_name,
            'gym_desc' => $this->gym_desc,
            'gym_address' => $this->gym_address,
            'show_widgets' => (int)$this->show_widgets, // Ensure 0 or 1 is sent
        ]);
    }

    public function __sleep()
    {
        return [
            'id', 
            'contact_email', 
            'contact_phone', 
            'workout_session_expiry', 
            'max_capacity', 
            'min_workout_time', 
            'gym_banner', 
            'gym_name', 
            'gym_desc', 
            'gym_address', 
            'show_widgets'
        ];
    }

    public function __wakeup()
    {
        // Reinitialize the database connection if necessary
        $this->conn = Database::get_conn();
    }
}
