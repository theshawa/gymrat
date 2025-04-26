<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;

$sidebarActive = 3;
$pageStyles = ["../../admin.css"];


require_once "../../../../db/models/Customer.php";
require_once "../../../../alerts/functions.php";


$customer = new Customer();
if (!isset($_SESSION['customer'])){    
    try {
        $customer->id = $id;
        $customer->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
    }
    $_SESSION['customer'] = serialize($customer);
} else {
    $customer = unserialize($_SESSION['customer']);
}



$menuBarConfig = [
    "title" => "Edit " . $customer->fname . " " . $customer->lname,
    "showBack" => true,
    "goBackTo" => "/staff/admin/rats/view/index.php?id=$id",
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
                        <input type="text" id="edit-fname" name="customer_fname"
                            class="staff-input-primary staff-input-long" value="<?= $customer->fname ?>"
                            pattern="[a-zA-Z]+" title="First name should only contain letters.">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-lname">Last Name</label></h2>
                        <input type="text" id="edit-lname" name="customer_lname"
                            class="staff-input-primary staff-input-long" value="<?= $customer->lname ?>"
                            pattern="[a-zA-Z]+" title="Last name should only contain letters.">
                    </div> -->
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-email">Email</label></h2>
                        <input type="email" id="edit-email" name="customer_email"
                            class="staff-input-primary staff-input-long" value="<?= $customer->email ?>"
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address.">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-phone">Phone</label></h2>
                        <input type="text" id="edit-phone" name="customer_phone"
                            class="staff-input-primary staff-input-long" value="<?= $customer->phone ?>"
                            pattern="\d{10}" title="Phone number must be a 10-digit number.">
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="edit-avatar">Avatar</label></h2>
                        <input type="file" id="edit-avatar" name="customer_avatar" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>