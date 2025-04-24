<?php

require_once __DIR__ . "/../Model.php";

class LogRecord extends Model
{
    protected $table = "log_records";

    public int $id;
    public int $equipment_id;
    public string $description;
    public string $status;
    public DateTime $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->equipment_id = $data['equipment_id'] ?? 0;
        $this->description = $data['description'] ?? "";
        $this->status = $data['status'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (equipment_id, description, status) VALUES (:equipment_id, :description, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'equipment_id' => $this->equipment_id,
            'description' => $this->description,
            'status' => $this->status,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET 
            equipment_id = :equipment_id, 
            description = :description, 
            status = :status 
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'equipment_id' => $this->equipment_id,
            'description' => $this->description,
            'status' => $this->status,
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

    public function get_by_id(int $id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->fill($data);
        }
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return array_map(function ($item) {
            $logRecord = new LogRecord();
            $logRecord->fill($item);
            return $logRecord;
        }, $data);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id]);
    }

    public function __sleep()
    {
        return ['id', 'equipment_id', 'description', 'status', 'created_at'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }
}
