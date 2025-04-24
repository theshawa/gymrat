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

    public function get_all_of_user(int $user)
    {
        $sql = "SELECT * FROM $this->table WHERE customer = :customer";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'customer' => $user
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPayment();
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

    public function get_total_revenue_for_month(int $year, int $month, int $membership_plan = 0): float
    {
        $sql = "SELECT SUM(amount) as total_revenue FROM $this->table 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month AND completed_at IS NOT NULL";
        $params = [
            'year' => $year,
            'month' => $month
        ];

        if ($membership_plan !== 0) {
            $sql .= " AND membership_plan = :membership_plan";
            $params['membership_plan'] = $membership_plan;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0.0;
    }

    public function get_total_count_for_month(int $year, int $month, int $membership_plan = 0): float
    {
        $sql = "SELECT COUNT(id) as total_revenue FROM $this->table 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month AND completed_at IS NOT NULL";
        $params = [
            'year' => $year,
            'month' => $month
        ];

        if ($membership_plan !== 0) {
            $sql .= " AND membership_plan = :membership_plan";
            $params['membership_plan'] = $membership_plan;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0.0;
    }

    public function get_all_sales_for_month(int $year, int $month): array
    {
        $sql = "SELECT * FROM $this->table WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month AND completed_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'year' => $year,
            'month' => $month
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPayment();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function get_all_sales_for_year(int $year): array
    {
        $sql = "SELECT * FROM $this->table WHERE YEAR(created_at) = :year AND completed_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'year' => $year
        ]);
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPayment();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function get_all_sales_grouped_by_plan_for_month(int $year, int $month): array
    {
        $sql = "SELECT membership_plan, SUM(amount) as total_amount, COUNT(id) as total_count 
                FROM $this->table 
                WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month AND completed_at IS NOT NULL
                GROUP BY membership_plan";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'year' => $year,
            'month' => $month
        ]);
        return $stmt->fetchAll();
    }

    public function get_all(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll();
        return array_map(function ($item) {
            $record = new MembershipPayment();
            $record->fill($item);
            return $record;
        }, $items);
    }

    public function __sleep()
    {
        return ['id', 'customer', 'membership_plan', 'amount', 'completed_at', 'created_at'];
    }

    public function __wakeup()
    {
        $this->conn = Database::get_conn();
    }
}
