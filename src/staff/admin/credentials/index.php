<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../db/models/Staff.php";
require_once "../../../alerts/functions.php";

$staff = null;
$staffModel = new Staff();
try {
    $staff = $staffModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch staff: " . $e->getMessage(), "/staff/admin");
    exit;
}



$pageTitle = "Manage Staff Credentials";
$sidebarActive = 8;
$pageStyles = ["../admin.css"];
$menuBarConfig = [
    "title" => $pageTitle
];

require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <?php foreach ($staff as $member) : ?>
            <a href="/staff/admin/credentials/view/index.php?id=<?= $member->id ?>" class="staff-list-item">
                <div style="grid-column:1;">
                    <h3><?= $member->name ?></h3>
                    <p><?= $member->email ?></p>
                </div>
                <div style="grid-column:2; align-self: end; justify-self: end; text-align: right;">
                    <p><strong>
                        <?php 
                            if ($member->role === 'admin') {
                                echo "Admin";
                            } elseif ($member->role === 'eq') {
                                echo "Equipment Manager";
                            } elseif ($member->role === 'wnmp') {
                                echo "WNMP Manager";
                            } else {
                                $member->role;
                            }
                        ?>
                    </strong></p>
                </div>
            </a>
        <?php endforeach; ?>
        <div>
            <!-- <pre><?= print_r($staff) ?></pre> -->
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>