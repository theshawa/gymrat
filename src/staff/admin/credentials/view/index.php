<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../db/models/Staff.php";
require_once "../../../../alerts/functions.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect_with_error_alert("Announcement ID is required.", "/staff/admin/credentials");
    exit;
}

$staff = new Staff();
try {
    $staff->id = $id;
    $staff->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch staff: " . $e->getMessage(), "/staff/admin/credentials");
    exit;
}


$pageTitle = $staff->name;
$sidebarActive = 9;
$pageStyles = ["../../admin.css"];
$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/credentials/index.php",
    "useLink" => true,
    "options" => []
];

// if ($staff->email !== "admin@gymrat.com" && $staff->role === "admin") {
//     $menuBarConfig["options"] = [
//         ["title" => "Edit Role", "href" => "/staff/admin/credentials/edit/index.php", "type" => "secondary"],
//         ["title" => "Delete Role", "href" => "/staff/admin/credentials/delete/index.php", "type" => "destructive"]
//     ];
// }

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin:20px;">

            <!-- Default Right -->
            <div class="staff-view-profile">
                <div style="grid-row: 1; grid-column: 1; align-self: start; 
                justify-self: start; text-align: left; padding: 20px;">
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
                <div style="grid-row: 2; grid-column: 1; align-self: end; 
                justify-self: start; text-align: left; padding: 20px;">
                    <h1 style="font-size: 28px; margin: 2px 0;"><?= $staff->name ?></h1>
                    <h1><?= $staff->email ?></h1>
                    <h3>Created On <?= $staff->created_at->format('Y-m-d') ?></h3>
                </div>
                <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; 
                text-align: right; padding: 20px;" class="text-gray">
                    <h1><?= $staff->role ?></h1>
                </div>
            </div>

            <!-- Admin Left -->
            <?php if ($staff->role === "admin"): ?>
                <div style="display: grid; grid-template-rows: 1fr 1fr;">
                <div style="grid-row: 2; align-self: end; justify-self: end; text-align: right;">
                    <h1 style="margin: 3px 0 ;">Admin</h1>
                    <p>The Admin is the central authority in the GYMRAT system, managing users and operations. Responsibilities include maintaining profiles for gym members, trainers, and staff, assigning trainers, managing membership plans, posting announcements, and handling system configurations. Admins resolve complaints, monitor subscriptions, and ensure the app remains functional, secure, and aligned with gym needs. With full access privileges, they optimize user engagement and play a key role in maintaining service quality and operational success.</p>
                </div>
                </div>
            <?php endif; ?>

            <!-- WNMP Left -->
            <?php if ($staff->role === "wnmp"): ?>
                <div style="display: grid; grid-template-rows: 1fr 1fr;">
                <div style="grid-row: 2; align-self: end; justify-self: end; text-align: right;">
                    <h1 style="margin: 3px 0 ;">Workout & Meal Plan Manager</h1>
                    <p>The Workout and Meal Plan Manager plays a key role in delivering personalized fitness and nutrition solutions. They create and maintain a database of workouts and meal plans tailored to diverse fitness goals, dietary needs, and training intensities. They handle trainer requests for custom plans, ensuring members receive specific guidance when needed. Additionally, they oversee the quality of nutritional information and ensure compliance with health standards. Responsibilities include managing meal items, exercises, and video tutorials to enhance user understanding. This role blends exercise science, nutrition expertise, and digital management to empower trainers and improve member satisfaction and progress.</p>
                </div>
                </div>
            <?php endif; ?>

            <!-- EQ Left -->
            <?php if ($staff->role === "eq"): ?>
                <div style="display: grid; grid-template-rows: 1fr 1fr;">
                <div style="grid-row: 2; align-self: end; justify-self: end; text-align: right;">
                    <h1 style="margin: 3px 0 ;">Equipment Manager</h1>
                    <p>The Equipment Manager ensures the integrity and availability of all gym equipment within the GYMRAT system. They manage the inventory, log equipment conditions, and coordinate timely repairs. Responsibilities include filing maintenance requests, tracking repairs, and documenting equipment status to alert Admins of hazards or replacements. By monitoring equipment health, they reduce injury risks and minimize downtime, enhancing the workout experience. This role requires attention to detail and knowledge of gym operations and maintenance. Equipment Managers ensure accurate equipment availability in the app, fostering a safe, efficient, and well-maintained environment for seamless workouts.</p>
                </div>
                </div>
            <?php endif; ?>

        </div>
        
    </div>
</main>
