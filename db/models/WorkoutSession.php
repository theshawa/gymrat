<?php

require_once __DIR__ . "/../Model.php";

class WorkoutSession extends Model
{
    protected $table = "workout_sessions";

    public int $id;
    public int $user;
    public int $workout;
    public DateTime $started_at;
    public DateTime|null $ended_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->user = $data['user'] ?? 0;
        $this->workout = $data['workout'] ?? 0;
        $this->started_at = new DateTime($data['started_at'] ?? null);
        $this->ended_at = $data['ended_at'] ? new DateTime($data['ended_at']) : null;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (user, workout) VALUES (:user, :workout)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $this->user,
            'workout' => $this->workout,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function mark_ended()
    {
        $sql = "UPDATE $this->table SET ended_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
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

    public function get_all_by_user()
    {
        $sql = "SELECT * FROM $this->table WHERE user=:user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user' => $this->user]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $ws = new WorkoutSession();
            $ws->fill($item);
            return $ws;
        }, $items);
    }

    public function get_all_live()
    {
        $sql = "SELECT * FROM $this->table WHERE ended_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $ws = new WorkoutSession();
            $ws->fill($item);
            return $ws;
        }, $items);
    }

    public function mark_all_live_as_ended_of_user(int $user)
    {
        $sql = "UPDATE $this->table SET ended_at=CURRENT_TIMESTAMP WHERE ended_at IS NULL AND user=:user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user' => $user,
        ]);
    }
}
