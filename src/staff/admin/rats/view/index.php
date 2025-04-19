<?php

$id = $_GET['id'] ?? null;

$sidebarActive = 3;
$pageStyles = ["../../admin.css"];

require_once "../../../../db/models/Customer.php";

$customer = new Customer();
try {
    $customer->id = $id;
    $customer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin");
    exit;
}


$menuBarConfig = [
    "title" => $customer->fname . " " . $customer->lname,
    "showBack" => true,
    "goBackTo" => "/staff/admin/rats/index.php",
];



require_once "../../pageconfig.php";

require_once "../../../../alerts/functions.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="rat-view-profile">
                <div>
                <?php if (!empty($customer->avatar)): ?>
                    <img src="../../../../<?= htmlspecialchars($customer->avatar) ?>" alt="Customer Avatar" >
                <?php else: ?>
                    <img src="../../../../uploads/default-images/default-avatar.png" alt="Default Avatar" class="rat-view-avatar">
                <?php endif; ?>
                </div>
                <div class="rat-view-row">
                    <h1>Name : </h1>
                    <p>&emsp;<?= $customer->fname . " " . $customer->lname ?></p>
                </div>
                <div class="rat-view-row">
                    <h1>Email : </h1>
                    <p>&emsp;<?= $customer->email ?></p>
                </div>
                <div class="rat-view-row">
                    <h1>Phone : </h1>
                    <p>&emsp;<?= $customer->phone ?></p>
                </div>
                <div class="rat-view-row">
                    <h1>Created On : </h1>
                    <p>&emsp;<?= $customer->created_at ? $customer->created_at->format('Y-m-d') : 'N/A' ?></p>
                </div>
            </div>
            <div>
                <pre><? print_r($customer) ?></pre>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>