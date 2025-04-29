<?php
session_start();
require_once "../../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/MembershipPayment.php";
require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/MembershipPlan.php";
require_once "../../../../../db/models/Settings.php";
require_once "../../../../../utils.php";
require_once "../../../../../generate-pdf.php";

if (!isset($_SESSION['sales_data'])) {
    redirect_with_error_alert("Failed to fetch sales details.", "/staff/admin/finance/sales");
    exit;
}

$settings = new Settings();
try {
    $settings->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch settings: " . $e->getMessage(), "/staff/admin/finance/sales");
    exit;
}

$customers = null;
$customersModel = new Customer();
try {
    $customers = $customersModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin/finance/sales");
    exit;
}

// Convert $customers to an associative array indexed by customer ID
$customers_by_id = [];
foreach ($customers as $customer) {
    $customers_by_id[$customer->id] = $customer;
}

$sales_data = unserialize($_SESSION['sales_data']);
$report_title = isset($_POST['report_title']) ? $_POST['report_title'] : "";
$record_count = isset($_POST['record_count']) ? $_POST['record_count'] : 0;
$total_revenue = isset($_POST['total_revenue']) ? $_POST['total_revenue'] : 0;
$report_description = isset($_POST['report_description']) ? $_POST['report_description'] : "";
$generated_date = date("Y-m-d H:i:s");
$generated_by = "Admin";

$total_amount = 0;
$sales_data_html = "";
foreach ($sales_data as $payment) {
    $total_amount += $payment->amount;
    $customer_name = isset($customers_by_id[$payment->customer]) 
        ? htmlspecialchars($customers_by_id[$payment->customer]->fname . " " . $customers_by_id[$payment->customer]->lname) 
        : "Unknown Customer";

    $sales_data_html .= "<tr>
        <td>" . htmlspecialchars($payment->id) . "</td>
        <td>" . htmlspecialchars($payment->customer) . "</td>
        <td>" . $customer_name . "</td>
        <td>" . htmlspecialchars($payment->membership_plan) . "</td>
        <td>" . htmlspecialchars($payment->completed_at->format('Y-m-d H:i:s')) . "</td>
        <td style='text-align: right;'>Rs." . htmlspecialchars(number_format($payment->amount, 2)) . "</td>
    </tr>";
}

$sales_data_html .= "<tr>
    <td colspan='5' style='text-align: right;'><strong>Total:</strong></td>
    <td style='text-align: right;'><strong>Rs." . htmlspecialchars(number_format($total_amount, 2)) . "</strong></td>
</tr>";

$htmlBody = <<<HTML
<link rel="stylesheet" href="/staff/admin/finance/report/styles.css">
<div class="custom-header">
    <div class="logo">
        <svg width="150" viewBox="0 0 94 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: grey;">
            <path
                        d="M16.1887 5.34857H12.4129V4.57143L11.6667 3.81714H3.00712L3.75325 4.57143V11.4286L4.49938 12.2057H11.6667L12.4129 11.4286V9.92H8.27524V6.10286H16.1887V12.96L13.1816 16H3.00712L0 12.96V3.06286L3.00712 0H13.1816L16.1887 3.06286V5.34857Z"
                        fill="currentColor" />
                <path
                        d="M28.9775 0H33.4995L26.7165 9.92V16H22.9406V9.92L16.1802 0H20.7022L24.8399 6.05714L28.9775 0Z"
                        fill="currentColor" />
                <path
                        d="M48.1717 0H51.925V16H48.1717V6.05714L45.142 9.92H41.3888L38.359 6.05714V16H34.6058V0H38.359L43.2654 6.28572L48.1717 0Z"
                        fill="currentColor" />
                <path
                        d="M57.6895 16H55.1345V0.0457151H61.9401C63.357 0.0457151 64.4725 0.480001 65.2864 1.34857C66.1004 2.2019 66.5074 3.36762 66.5074 4.84571C66.5074 5.98857 66.2436 6.94095 65.716 7.70286C65.2035 8.44952 64.4423 8.97524 63.4324 9.28L66.8465 16H63.9977L60.8323 9.55429H57.6895V16ZM61.7593 7.38286C62.4074 7.38286 62.9124 7.21524 63.2741 6.88C63.6359 6.52952 63.8168 6.03429 63.8168 5.39429V4.29714C63.8168 3.65714 63.6359 3.16952 63.2741 2.83429C62.9124 2.48381 62.4074 2.30857 61.7593 2.30857H57.6895V7.38286H61.7593Z"
                        fill="currentColor" />
                <path
                        d="M79.8082 16L78.3838 11.68H72.4826L71.1034 16H68.5032L73.8844 0.0457151H77.095L82.4762 16H79.8082ZM75.4897 2.42286H75.3766L73.093 9.46286H77.7507L75.4897 2.42286Z"
                        fill="currentColor" />
                <path d="M89.2971 2.33143V16H86.7422V2.33143H82.0393V0.0457151H94V2.33143H89.2971Z"
                    fill="currentColor" />
        </svg>
    </div>
    <div class="gym-info">
        <h1>{$settings->gym_name}</h1>
        <p>{$settings->gym_address}</p>
    </div>
</div>
<div class="report-title">
    <h2>{$report_title}</h2>
</div>
<div class="report-details">
    <table>
        <tbody>
            <tr>
                <td colspan="6"><strong>Description : </strong> {$report_description}</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Record Count : </strong> {$record_count}</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Generated By : </strong> {$generated_by}</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Generated Date : </strong> {$generated_date}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="sales-data">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer ID</th>
                <th>Customer</th>
                <th>Membership Plan</th>
                <th>Completed At</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            {$sales_data_html}
        </tbody>
    </table>
</div>
HTML;

$file_name = "sales_report_" . str_replace(" ", "_", strtolower($report_title)) . ".pdf";
generate_pdf($htmlBody, $file_name);
