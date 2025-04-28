<?php

require_once __DIR__ . "/../Model.php";

class LogRecord extends Model
{
    protected $table = "equipment_log_records";

    public int $id;
    public int $equipment_manager;
    public string $description;
    public DateTime $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->equipment_manager = $data['equipment_manager'] ?? 0;
        $this->description = $data['description'] ?? "";
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (equipment_manager, description) VALUES (:equipment_manager, :description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'equipment_manager' => $this->equipment_manager,
            'description' => $this->description,
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET 
            equipment_manager = :equipment_manager, 
            description = :description 
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
            'equipment_manager' => $this->equipment_manager,
            'description' => $this->description,
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
        return ['id', 'equipment_manager', 'description', 'created_at'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }
}
