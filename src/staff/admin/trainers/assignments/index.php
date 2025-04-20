<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Trainer.php";


$sidebarActive = 4;
$pageStyles = ["../../admin.css"];

$trainer = new Trainer();
try {
    $trainer->id = $id;
    $trainer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch trainer: " . $e->getMessage(), "/staff/admin");
    exit;
}
$_SESSION['trainer'] = serialize($trainer);


$menuBarConfig = [
    "title" => $trainer->fname . " " . $trainer->lname . " Assignments",
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers/view/index.php?id=" . $trainer->id,
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

            <div class="trainer-view-profile alt">
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                    <?php if (!empty($trainer->avatar)): ?>
                        <img src="../../../../uploads/<?= $trainer->avatar ?>" alt="Trainer Avatar"  class="trainer-view-avatar">
                    <?php else: ?>
                        <img src="../../../../uploads/default-images/default-avatar.png" alt="Default Avatar" class="trainer-view-avatar">
                    <?php endif; ?>
                    </div>
                    <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: center; text-align: center;">
                        <h1 style="margin: 10px; font-size: 28px;"><?= $trainer->fname . " " . $trainer->lname ?></h1>
                    </div>
                </div>
            

        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
