<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Customer.php";

$customer = new Customer();
try {
    $customer->id = $id;
    $customer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customer: " . $e->getMessage(), "/staff/admin/rats");
    exit;
}

$menuBarConfig = [
    "title" => "Delete Customer",
    "showBack" => true,
    "goBackTo" => "/staff/admin/rats",
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

            <div class="rat-view-profile alt">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                <?php if (!empty($customer->avatar)): ?>
                    <img src="../../../../uploads/<?= $customer->avatar ?>" alt="Customer Avatar" class="rat-view-avatar">
                <?php else: ?>
                    <img src="../../../../uploads/default-images/infoCardDefault.png" alt="Default Avatar" class="rat-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: center; text-align: center;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $customer->fname . " " . $customer->lname ?></h1>
                </div>
            </div>

            <div style="grid-column: 2; align-self: start; justify-self: end; text-align: right; width:100%;">
                <h1 style="margin-bottom: 20px;">Delete Customer</h1>
                <form action="delete_customer.php" method="POST">
                    <input type="hidden" name="customer_id" value="<?= $id ?>">
                    <div class="staff-record-delete-div">
                        <h2>Are you sure you want to delete this customer?</h2>
                        <p>This action cannot be undone.</p>
                        <button type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
