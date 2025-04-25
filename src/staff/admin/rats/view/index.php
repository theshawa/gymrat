<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
$assignTrainer = $_GET['assign'] ?? 0;
$confirmTrainer = $_GET['confirm'] ?? 0;

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Customer.php";
require_once "../../../../db/models/Trainer.php";
require_once "../../../../db/models/MembershipPlan.php";

if ($assignTrainer && $confirmTrainer) {
    redirect_with_error_alert("Conflicting operations in assigning trainer", "/staff/admin/rats/view/index.php?id=$id");
    exit;
}


$sidebarActive = 3;
$pageStyles = ["../../admin.css"];



$customer = new Customer();
try {
    $customer->id = $id;
    $customer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin");
    exit;
}
$_SESSION['customer'] = serialize($customer);



$membership_plan = new MembershipPlan();
$membership_plan->id = $customer->membership_plan;
if (!isset($_SESSION['membership_plan'])) {
    try {
        $membership_plan->get_by_id();
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to fetch membership plan title: " . $e->getMessage();
    }
} else {
    $membership_plan = unserialize($_SESSION['membership_plan']);
}


$membership_plan_expiration = null;
if ($customer->membership_plan_activated_at) {
    $membership_plan_expiration = clone $customer->membership_plan_activated_at;
    $membership_plan_expiration->modify("+{$membership_plan->duration} days");
}


// Have different back to logic for stages of assigning trainer
$goBackTo = "/staff/admin/rats/index.php";
if ($assignTrainer) $goBackTo = "/staff/admin/rats/view/index.php?id=$id";
if ($confirmTrainer) $goBackTo = "/staff/admin/rats/view/index.php?id=$id&assign=1";

$menuBarConfig = [
    "title" => $customer->fname . " " . $customer->lname,
    "showBack" => true,
    "goBackTo" => $goBackTo ,
];


// Retreive trainer titles if Assigning Trainer Stage
$trainerNames = null;
if ($assignTrainer) {
    $trainerModel = new Trainer();
    try {
        $trainerNames = $trainerModel->get_all_trainers();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch trainers: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
    }
}

// Retreive trainer detail if Confirming Trainer Stage 
$trainer = null;
$customersAssignedCount = null;
if ($confirmTrainer ) {
    $trainer = new Trainer();
    $trainer->id = $confirmTrainer;
    try {
        $trainer->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch trainer detail: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$id");
        exit;
    }

    $customerModel = new Customer();
    try {
        $customersAssignedCount = $customerModel->count_customers_by_trainer($trainer->id);
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to fetch count of customers assigned to trainer: " . $e->getMessage();
    }
}

// Retreive trainer if customer already has trainer assigned
if (!$confirmTrainer && $customer->trainer) {
    $trainer = new Trainer();
    $trainer->id = $customer->trainer;
    try {
        $trainer->get_by_id();
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to fetch count of trainer details: " . $e->getMessage();
    }
}


require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>

        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

            <!-- Deafult Right Layout -->
            <div class="rat-view-profile">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                <?php if (!empty($customer->avatar)): ?>
                    <img src="../../../../uploads/<?= $customer->avatar ?>" alt="Customer Avatar"  class="rat-view-avatar">
                <?php else: ?>
                    <img src="../../../../uploads/default-images/infoCardDefault.png" alt="Default Avatar" class="rat-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $customer->fname . " " . $customer->lname ?></h1>
                    <h1 style="margin: 10px;"><?= $customer->email ?></h1>
                    <h1 style="margin: 10px;"><?= $customer->phone ?></h1>
                    <h3 style="margin: 10px;">Created on <?= $customer->created_at ? $customer->created_at->format('Y-m-d') : 'N/A' ?></h3>
                </div>
                <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                    <a href="/staff/admin/rats/profile/index.php?id=<?= $customer->id ?>" style="margin: 10px 0px; width: 150px; height: 40px;" 
                    class="staff-button secondary">Edit Profile</a>
                </div>
            </div>


            <!-- Default Left Layout -->
            <?php if (!$assignTrainer && !$confirmTrainer): ?>
                <div class="rat-view-menu">

                    <div class="rat-view-trainer">
                        <div style="grid-row: 1;align-self: start; justify-self: end; text-align: right;">
                            <h1 style="margin: 10px; font-size: 24px;">Trainer</h1>
                            <?php if ($customer->trainer): ?>
                                <div style="margin: 20px 0;">
                                    <h1 style="margin: 10px;"><?= $trainer->fname . " " . $trainer->lname ?></h1>
                                    <p style="margin: 10px;"><?= $trainer->bio ?></p>
                                </div>
                            <?php else: ?>
                                <p style="margin: 10px;">No trainer assigned</p>
                            <?php endif; ?>
                        </div>
                        <div style="grid-row: 2; align-self: end; justify-self: end; text-align: right;">
                            <a href="/staff/admin/rats/view/index.php?id=<?= $customer->id ?>&assign=1" style="margin: 10px 0px; width: 200px; height: 40px;" 
                               class="staff-button <?= ($customer->trainer ? 'secondary' : 'destructive') ?>">
                                <?= ($customer->trainer ? "Change" : "Assign") ?> Trainer  
                            </a>
                        </div>
                    </div>
                    
                    <?php if ($customer->membership_plan): ?>
                    <div class="rat-view-membership">
                        <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                            <?php if ($membership_plan_expiration): ?>
                                <p style="margin: 2px 0px;">Expires on</p>
                                <h1><?= $membership_plan_expiration->format('Y-m-d') ?></h1>
                            <?php endif; ?>
                        </div>
                        <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left;">
                            <a href="/staff/admin/rats/membership/index.php?id=<?= $customer->id ?>" 
                            style="margin: 10px 0px; width: 120px; height: 40px;" class="staff-button primary">
                                Edit Plan
                            </a>
                        </div>
                        <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                            <p>Current Membership Plan</p>
                            <h1 style="margin: 10px; font-size: 28px;"><?= $membership_plan->name ?? $customer->membership_plan ?></h1>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <!-- Choosing a Trainer Layout -->
            <?php if ($assignTrainer): ?>
                <form action="assign_trainer.php" method="POST" class="rat-view-trainer-form">
                    <input type="hidden" name="customer_id" value="<?= $customer->id ?>">
                    <h1 style="margin: 10px 0;">Assign a Trainer</h1>
                    <p style="margin-top: 30px; margin-bottom: 10px;">Select a trainer from the list below:</p>
                    <select name="trainer_id" class="staff-input-primary staff-input-long">
                        <?php foreach ($trainerNames as $id => $name): ?>
                            <option value="<?= $id ?>" <?= ($customer->trainer && $customer->trainer == $id) ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="staff-button secondary" style="margin: 10px 0px; width: 150px; height: 40px;">
                        Confirm Trainer
                    </button>
                </form>
            <?php endif; ?>

            <!-- Confirming Trainer Layout -->
            <?php if ($confirmTrainer): ?>
                <form action="confirm_trainer.php" method="POST" class="rat-view-trainer-form">
                    <h1 style="margin: 10px 0;">Confirm Trainer</h1>
                    <h2 style="margin-top: 30px; margin-bottom: 10px;"><?= $trainer->fname . " " . $trainer->lname ?></h2>
                    <p style="margin: 10px 0;"><?= $trainer->bio ?></p>
                    <p style="margin: 10px 0;">Currently has <?= $customersAssignedCount ?> rats assigned to this trainer</p>

                    <input type="hidden" name="trainer_id" value="<?= $trainer->id ?>">
                    <button type="submit" class="staff-button secondary" style="margin: 20px 0px; width: 150px; height: 40px;">
                        Assign Trainer
                    </button>
                </form>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>