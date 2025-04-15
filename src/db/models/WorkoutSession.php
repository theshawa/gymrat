<?php

require_once __DIR__ . "/../Model.php";

class WorkoutSession extends Model
{
    protected $table = "workout_sessions";

    public string $session_key;
    public int $user;
    public int $workout;
    public DateTime $started_at;
    public DateTime|null $ended_at;

    public function fill(array $data)
    {
        $this->session_key = $data['session_key'] ?? 0;
        $this->user = $data['user'] ?? 0;
        $this->workout = $data['workout'] ?? 0;
        $this->started_at = new DateTime($data['started_at'] ?? '');
        $this->ended_at = isset($data['ended_at']) ? new DateTime($data['ended_at']) : null;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (session_key, user, workout) VALUES (:session_key, :user, :workout)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'session_key' => $this->session_key,
            'user' => $this->user,
            'workout' => $this->workout,
        ]);
    }

    public function get_by_session_key()
    {
        $sql = "SELECT * FROM $this->table WHERE session_key=:session_key";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['session_key' => $this->session_key]);
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

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE session_key=:session_key";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'session_key' => $this->session_key,
        ]);
    }

    public function get_duration_in_hours(): float
    {
        if ($this->ended_at) {
            $start_time = strtotime($this->started_at->format("Y-m-d H:i:s"));
            $end_time = strtotime($this->ended_at->format("Y-m-d H:i:s"));
        } else {
            $start_time = strtotime($this->started_at->format("Y-m-d H:i:s"));
            $end_time = strtotime((new DateTime("now", new DateTimeZone("Asia/Colombo")))->format("Y-m-d H:i:s"));
        }
        return ($end_time - $start_time) / 3600;
    }

    public function mark_ended(?DateTime $ended_at = null)
    {
        if ($this->get_duration_in_hours() < 1) {
            $this->delete();
            return;
        }
        if ($ended_at) {
            $sql = "UPDATE $this->table SET ended_at=:ended_at WHERE session_key=:session_key";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'ended_at' => $ended_at->format("Y-m-d H:i:s"),
                'session_key' => $this->session_key,
            ]);
        } else {
            $sql = "UPDATE $this->table SET ended_at=CURRENT_TIMESTAMP WHERE session_key=:session_key";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'session_key' => $this->session_key,
            ]);
        }
    }

    public function mark_all_live_as_ended_of_user(int $user, ?DateTime $ended_at = null)
    {
        if ($ended_at) {
            $sql = "UPDATE $this->table SET ended_at=:ended_at WHERE ended_at IS NULL AND user=:user";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'ended_at' => $ended_at->format("Y-m-d H:i:s"),
                'user' => $user,
            ]);
        } else {
            $sql = "UPDATE $this->table SET ended_at=CURRENT_TIMESTAMP WHERE ended_at IS NULL AND user=:user";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'user' => $user,
            ]);
        }
    }
}
