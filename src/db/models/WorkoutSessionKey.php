<?php

require_once __DIR__ . "/../Model.php";

class WorkoutSessionKey extends Model
{
    protected $table = "workout_session_keys";

    public string $session_key;
    public DateTime $created_at;

    public function fill(array $data)
    {
        $this->session_key = $data['session_key'] ?? "";
    }

    private function generate_random_unique_key(string $prev_key): string
    {
        return "gymrat_wsk_" . hash('sha256', $prev_key . microtime(true) . random_bytes(10));
    }

    public function create(string $current_key)
    {
        $this->session_key = $this->generate_random_unique_key($current_key);
        $sql = "INSERT INTO $this->table (session_key) VALUES (:session_key)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'session_key' => $this->session_key,
        ]);
    }

    public function delete_all()
    {
        $sql = "DELETE FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }

    public function get_by_key()
    {
        $sql = "SELECT * FROM $this->table WHERE session_key = :session_key LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'session_key' => $this->session_key,
        ]);
        $item = $stmt->fetch();
        if (!$item) {
            throw new Exception("Session key not found");
        }
        $this->fill($item);
    }

    public function get_one()
    {
        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $item = $stmt->fetch();
        if (!$item) {
            throw new Exception("Session key not found");
        }
        $this->fill($item);
    }
}
