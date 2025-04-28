<?php
require_once "../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

require_once "../../alerts/functions.php";
require_once "../../db/models/Settings.php";

$pageTitle = "Home";
$pageStyles = ["./eq.css"];
$sidebarActive = 1;
$menuBarConfig = [
    "title" => $pageTitle
];

$settings = new Settings();
try {
    $settings->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}

require_once "./pageconfig.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../includes/menubar.php"; ?>
        <div class="dashboard-container">
            <div class="dashboard-col-primary">
            <?php if ($settings->gym_banner): ?>
                <div class="dashboard-tab-large alt" 
                style="position: relative; display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center; width: 100%; height: 100%;">
                    <style>
                        .dashboard-tab-large.alt::before {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: url('../../uploads/<?= $settings->gym_banner ?>') center center / cover no-repeat;
                            filter: blur(8px) brightness(0.5);
                            z-index: -1;
                        }
                    </style>
            <?php else: ?>
                <div class="dashboard-tab-large" 
                style="display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center;">
            <?php endif; ?>
                    <?php if ($settings->show_widgets): ?>
                        <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                            <h1 class="font-zinc-200" style="font-size: 28px;"><?= $settings->gym_name ?></h1>
                        </div>
                    <?php endif; ?>
                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 class="font-zinc-200">Welcome Back, Equipment Manager!</h1>
                    </div>
                </div>
            </div>
            <div class="dashboard-col-secondary">
                <a a href="/staff/eq/equipments/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Equipments</h1>
                </a>
                <a href="/staff/eq/log-records/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Log Records</h1>
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>