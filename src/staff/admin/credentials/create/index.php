<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$sidebarActive = 9;

require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Create Credential",
    "showBack" => true,
    "goBackTo" => "/staff/admin/credentials/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"]
    ]
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../admin.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_credential.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-name">Name</label></h2>
                        <input type="text" id="edit-name" name="staff_name"
                            class="staff-input-primary staff-input-long" placeholder="Enter name">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-email">Email</label></h2>
                        <input type="email" id="edit-email" name="staff_email"
                            class="staff-input-primary staff-input-long" placeholder="Enter email">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-password">Password</label></h2>
                        <input type="password" id="edit-password" name="staff_password"
                            class="staff-input-primary staff-input-long" placeholder="Enter password">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-confirm-password">Confirm Password</label></h2>
                        <input type="password" id="edit-confirm-password" name="staff_confirm_password"
                            class="staff-input-primary staff-input-long" placeholder="Confirm password">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-role">Role</label></h2>
                        <select id="edit-role" name="staff_role" class="staff-input-primary staff-input-long">
                            <option value="wnmp">Workout & Meal Plan Manager</option>
                            <option value="eq">Equipment Manager</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>