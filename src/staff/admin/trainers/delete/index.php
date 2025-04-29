<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Trainer.php";

$trainer = new Trainer();
try {
    $trainer->id = $id;
    $trainer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch trainer: " . $e->getMessage(), "/staff/admin/trainers");
    exit;
}

$menuBarConfig = [
    "title" => "Delete Trainer",
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers",
];

$pageStyles = ["../../admin.css"];

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
                    <img src="../../../../uploads/<?= $trainer->avatar ?>" alt="Trainer Avatar" class="trainer-view-avatar">
                <?php else: ?>
                    <img src="../../../../uploads/default-images/default-avatar.png" alt="Default Avatar" class="trainer-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: center; text-align: center;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $trainer->fname . " " . $trainer->lname ?></h1>
                </div>
            </div>

            <div style="grid-column: 2; align-self: start; justify-self: end; text-align: right; width:100%;">
                <h1 style="margin-bottom: 20px;">Delete Trainer</h1>
                <form action="delete_trainer.php" method="POST">
                    <input type="hidden" name="trainer_id" value="<?= $id ?>">
                    <div class="staff-record-delete-div">
                        <h2>Are you sure you want to delete this trainer?</h2>
                        <p>This action cannot be undone.</p>
                        <button type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
