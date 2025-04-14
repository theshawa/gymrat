<?php

require_once __DIR__ . "/../Model.php";

class MembershipPayment extends Model
{
    protected $table = "membership_payments";

    public int $id;
    public int $customer;
    public int $membership_plan;
    public float $amount;
    public ?DateTime $completed_at = null;
    public DateTime $created_at;

    public function fill(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->customer = $data['customer'] ?? 0;
        $this->membership_plan = $data['membership_plan'] ?? 0;
        $this->amount = $data['amount'] ?? 0.0;
        $this->completed_at = isset($data['completed_at']) ? new DateTime($data['completed_at']) : null;
        $this->created_at = new DateTime($data['created_at'] ?? '');
    }

    public function get_by_id()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id
        ]);
        $item = $stmt->fetch();
        if ($item) {
            $this->fill($item);
        } else {
            throw new Exception("Payment not found");
        }
    }

    public function get_all_of_user(int $user): array
    {
        $sql = "SELECT * FROM $this->table WHERE customer = :customer";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer' => $user
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPlan();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function get_all_of_membership_plan(int $plan_id): array
    {
        $sql = "SELECT * FROM $this->table WHERE membership_plan = :membership_plan";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'membership_plan' => $plan_id
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPayment();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id
        ]);
    }

    public function delete_all_of_user(int $user)
    {
        $sql = "DELETE FROM $this->table WHERE customer = :customer";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer' => $user
        ]);
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (customer, membership_plan, amount, created_at) VALUES (:customer, :membership_plan, :amount, :created_at)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer' => $this->customer,
            'membership_plan' => $this->membership_plan,
            'amount' => $this->amount,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ]);
        $this->id = $this->conn->lastInsertId();
    }

    public function mark_completed()
    {
        $sql = "UPDATE $this->table SET completed_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $this->id,
        ]);
    }
}
