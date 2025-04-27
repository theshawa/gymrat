<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$sidebarActive = 4;
$pageStyles = ["../../admin.css"];

require_once "../../../../db/models/Trainer.php";

$trainer = new Trainer();
if (isset($_SESSION['trainer'])) {
    $trainer = unserialize($_SESSION['trainer']);
} else {
    $trainer->fill([]);
    $_SESSION['trainer'] = serialize($trainer);
}


$menuBarConfig = [
    "title" => "Create New Trainer",
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Create Trainer", "buttonType" => "submit", "buttonName" => "action", "buttonValue" => "create", "type" => "secondary"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_profile.php" method="POST" enctype="multipart/form-data">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-fname">First Name</label></h2>
                        <input type="text" id="create-fname" name="trainer_fname"
                            class="staff-input-primary staff-input-long" required
                            pattern="[a-zA-Z]+" title="First name should only contain letters."
                            value="<?= htmlspecialchars($trainer->fname ?? '') ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-lname">Last Name</label></h2>
                        <input type="text" id="create-lname" name="trainer_lname"
                            class="staff-input-primary staff-input-long" required
                            pattern="[a-zA-Z]+" title="Last name should only contain letters."
                            value="<?= htmlspecialchars($trainer->lname ?? '') ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-username">Username</label></h2>
                        <input type="text" id="create-username" name="trainer_username"
                            class="staff-input-primary staff-input-long" required
                            pattern="[a-zA-Z0-9]+" title="Username should only contain letters and numbers.">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-phone">Phone</label></h2>
                        <input type="text" id="create-phone" name="trainer_phone"
                            class="staff-input-primary staff-input-long" required
                            pattern="\d{10}" title="Phone number must be a 10-digit number."
                            value="<?= htmlspecialchars($trainer->phone ?? '') ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-password">Password</label></h2>
                        <input type="password" id="create-password" name="trainer_password"
                            class="staff-input-primary staff-input-long" required>
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="create-confirm-password">Confirm Password</label></h2>
                        <input type="password" id="create-confirm-password" name="trainer_confirm_password"
                            class="staff-input-primary staff-input-long" required>
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="create-avatar">Avatar</label></h2>
                        <input type="file" id="create-avatar" name="trainer_avatar" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="create-bio">Bio</label></h2>
                        <textarea id="create-bio" name="trainer_bio" class="staff-input-primary"
                            rows="8" style="width: 70%"><?= htmlspecialchars($trainer->bio ?? '') ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
