<?php

session_start();

$id = $_GET['id'] ?? null;
$confirmMembership = $_GET['confirm'] ?? 0;

$sidebarActive = 3;
$pageStyles = ["../../admin.css"];


require_once "../../../../db/models/Customer.php";
require_once "../../../../db/models/MembershipPlan.php";
require_once "../../../../alerts/functions.php";

// Current Customer
$customer = new Customer();
if (!isset($_SESSION['customer'])){    
    try {
        $customer->id = $id;
        $customer->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch customer: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
    }
    $_SESSION['customer'] = serialize($customer);
} else {
    $customer = unserialize($_SESSION['customer']);
}

// Current Membership Plan
$membership_plan = new MembershipPlan();
$membership_plan->id = $customer->membership_plan;
if (!isset($_SESSION['membership_plan'])) {
    try {
        $membership_plan->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch customer's membership plan: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
    }
} else {
    $membership_plan = unserialize($_SESSION['membership_plan']);
}

// Current Plan Expiration Date
$membership_plan_expiration = null;
if ($customer->membership_plan_activated_at) {
    $membership_plan_expiration = clone $customer->membership_plan_activated_at;
    $membership_plan_expiration->modify("+{$membership_plan->duration} days");
}


// Membership Plan Options
$membership_options = null;
try {
    $membershipModel = new MembershipPlan();
    $membership_options = $membershipModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch membership plan options: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
}


// Selected Membership Plan
$selected_membership = null;
$selected_plan_expiration = new DateTime();
if ($confirmMembership) {
    foreach ($membership_options as $membershipPlan) {
        if ($membershipPlan->id == $confirmMembership) {
            $selected_membership = $membershipPlan;
            break;
        }
    }
    $selected_plan_expiration->modify("+{$selected_membership->duration} days");
}


$menuBarConfig = [
    "title" => "Edit Membership",
    "showBack" => true,
    "goBackTo" => "/staff/admin/rats/view/index.php?id=$id"
];



require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
    <?php require_once "../../../includes/menubar.php"; ?>
            <div style="margin: 20px; display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
                
                <div class="rat-view-profile" style="padding: 30px 40px;">
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: end; text-align: left;">
                    <?php if (!empty($customer->avatar)): ?>
                        <img src="../../../../uploads/<?= $customer->avatar ?>" alt="Customer Avatar"  class="rat-view-avatar">
                    <?php else: ?>
                        <img src="../../../../uploads/default-images/default-avatar.png" alt="Default Avatar" class="rat-view-avatar">
                    <?php endif; ?>
                    </div>
                    <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: center; text-align: center;">
                        <h1 style="margin: 10px;"><?= $customer->fname . " " . $customer->lname ?></h1>
                    </div>
                </div>

                <?php if (!($confirmMembership)): ?>
                <div>
                    <div style="margin-bottom: 60px;">
                        <h1>Current Membership Plan</h1>
                        <h1 style="margin-top: 40px; margin-bottom: 8px; font-size: 28px;"><?= $membership_plan->name ?? $customer->membership_plan ?></h1>
                        <p style="margin-bottom: 5px;"><?= $membership_plan->description ?></p>
                        <p>Expires on <?= $membership_plan_expiration->format('Y-m-d') ?></p>
                    </div>
                    <form action="assign_membership.php" method="POST" class="rat-view-trainer-form" style="margin: 0px;">
                        <input type="hidden" name="customer_id" value="<?= $customer->id ?>">
                        <h1 style="margin: 10px 0;">Edit Membership Plan</h1>
                        <p style="margin-top: 20px; margin-bottom: 10px;">Select a membership plan from the list below :</p>
                        <select name="membership_id" class="staff-input-primary staff-input-long">
                            <?php foreach ($membership_options as $membershipPlan): ?>
                                <option value="<?= $membershipPlan->id ?>" 
                                    <?= ($customer->membership_plan && $customer->membership_plan == $membershipPlan->id) ? 'selected' : '' ?>>
                                    <?= $membershipPlan->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="staff-button secondary" style="margin: 10px 0px; width: 200px; height: 40px;">
                            Select Membership
                        </button>
                    </form>
                </div>
                <?php endif; ?>


                <?php if ($confirmMembership): ?>
                <div>
                    <div style="margin-bottom: 60px;">
                        <h1>Current Membership Plan</h1>
                        <h1 style="margin-top: 40px; margin-bottom: 8px; font-size: 28px;"><?= $membership_plan->name ?? $customer->membership_plan ?></h1>
                        <p style="margin-bottom: 5px;"><?= $membership_plan->description ?></p>
                        <p>Expires on <?= $membership_plan_expiration->format('Y-m-d') ?></p>
                    </div>
                    <form action="confirm_membership.php" method="POST" class="rat-view-trainer-form" style="margin: 0px;">
                        <input type="hidden" name="customer_id" value="<?= $customer->id ?>">
                        <input type="hidden" name="membership_id" value="<?= $confirmMembership ?>">
                        <h1 style="margin: 10px 0;">Edit Membership Plan</h1>
                        <p style="margin-top: 20px; margin-bottom: 10px;">You have chosen :</p>
                        <h1 style="margin-top: 20px; margin-bottom: 8px; font-size: 28px;">
                            <?= $selected_membership->name ?? 'Unknown Membership' ?></h1>
                        <p style="margin-bottom: 5px;"><?= $selected_membership->description ?? 'No description available' ?></p>
                        <p>Expires on <?= $selected_plan_expiration->format('Y-m-d') ?></p>
                        <button type="submit" class="staff-button secondary" style="margin: 30px 0px; width: 200px; height: 40px;">
                            Confirm Membership
                        </button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>