<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../auth-guards.php";
if (auth_not_required_guard("staff", "/staff")) exit;

if (!isset($_SESSION['staff_password_reset']['verified'])) {
    die("Please verify your email first.");
}

$pageConfig = [
    "title" => "Reset Password | Forgot Password",
];


require_once "../../../includes/header.php";
?>

<main>
    <div style="display: flex; flex-direction: column; align-content: center; justify-content: center; width: 100%">
        <form action="reset_password_process.php" method="post" class="staff-card-container">
            <div style="margin: 50px 0px; display: flex; flex-direction: column; align-items: center;">
                <svg width="120" viewBox="0 0 94 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <h3 style="margin-top: 10px;">Enter New Password</h3>
            </div>
            <input  required type="password" placeholder="Enter new password" name="password" class="staff-input-primary staff-input-base">
            <button class="staff-button secondary" style="margin: 10px 0; padding: 20px;">Enter New Password</button>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php" ?>