<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;

$sidebarActive = 4;
$pageStyles = ["../../admin.css"];

require_once "../../../../db/models/Trainer.php";
require_once "../../../../alerts/functions.php";

$trainer = new Trainer();
if (!isset($_SESSION['trainer'])) {
    try {
        $trainer->id = $id;
        $trainer->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch trainer: " . $e->getMessage(), "/staff/admin/trainers/view/index.php?id=$id");
        exit;
    }
    $_SESSION['trainer'] = serialize($trainer);
} else {
    $trainer = unserialize($_SESSION['trainer']);
}

$menuBarConfig = [
    "title" => "Edit " . $trainer->fname . " " . $trainer->lname,
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "edit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <!-- <div style="margin-bottom: 10px">
                        <h2><label for="edit-fname">First Name</label></h2>
                        <input type="text" id="edit-fname" name="trainer_fname"
                            class="staff-input-primary staff-input-long" value="<?= $trainer->fname ?>"
                            pattern="[a-zA-Z]+" title="First name should only contain letters.">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-lname">Last Name</label></h2>
                        <input type="text" id="edit-lname" name="trainer_lname"
                            class="staff-input-primary staff-input-long" value="<?= $trainer->lname ?>"
                            pattern="[a-zA-Z]+" title="Last name should only contain letters.">
                    </div> -->
                    <!-- <div style="margin-bottom: 10px">
                        <h2><label for="edit-username">Username</label></h2>
                        <input type="text" id="edit-username" name="trainer_username"
                            class="staff-input-primary staff-input-long" value="<?= $trainer->username ?>"
                            pattern="[a-zA-Z0-9]+" title="Username should only contain letters and numbers.">
                    </div> -->
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-password">Password</label></h2>
                        <input type="password" id="create-password" name="trainer_password"
                            class="staff-input-primary staff-input-long">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-confirm-password">Confirm Password</label></h2>
                        <input type="password" id="create-confirm-password" name="trainer_confirm_password"
                            class="staff-input-primary staff-input-long">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-phone">Phone</label></h2>
                        <input type="text" id="edit-phone" name="trainer_phone"
                            class="staff-input-primary staff-input-long" value="<?= $trainer->phone ?>"
                            pattern="\d{10}" title="Phone number must be a 10-digit number.">
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="edit-avatar">Avatar</label></h2>
                        <input type="file" id="edit-avatar" name="trainer_avatar" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="edit-bio">Bio</label></h2>
                        <textarea id="edit-bio" name="trainer_bio" class="staff-input-primary"
                            rows="8" style="width: 70%"><?= $trainer->bio ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
