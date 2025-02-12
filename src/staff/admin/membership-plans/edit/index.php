<?php

$pageTitle = "Edit Membership Plan";
$pageStyles = ["../membership-plans.css"];
$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/membership-plans/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
    ]
];


require_once "../../pageconfig.php";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard_with_role("admin", "/staff/login");

require_once "../../../../db/models/MembershipPlan.php";

require_once "../../../../alerts/functions.php";


$id = htmlspecialchars($_GET["id"]);
$membershipPlan = new MembershipPlan();
$membershipPlan->fill([
    "id" => $id
]);
try {
    $membershipPlan->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch membership plan: " . $e->getMessage(), "/staff/admin/membership-plans");
}

?>

<main>
    <div class="staff-base-container">
<!--        <h1>Edit Membership Plan: --><?php //= $membershipPlan->name ?><!--</h1>-->
<!--        --><?php //if ($membershipPlan->is_locked): ?>
<!--            <p class="paragraph">-->
<!--                This plan is locked. New customers will not be able to see the changes you make.-->
<!--            </p>-->
<!--        --><?php //endif; ?>
<!--        <form action="edit_process.php" method="post" class="form">-->
        <form action="edit_process.php" method="post" class="form alt">
            <?php require_once "../../../includes/menubar.php"; ?>
            <h1>Edit Membership Plan: <?= $membershipPlan->name ?></h1>
            <?php if ($membershipPlan->is_locked): ?>
                <p class="paragraph">
                    This plan is locked. New customers will not be able to see the changes you make.
                </p>
            <?php endif; ?>
            <div style="margin: 5px 0;">
                <input value="<?= $membershipPlan->name ?>" type="text" class="staff-input-primary staff-input-long" placeholder="Name" required name="name" id="name">
            </div>
            <div style="margin: 5px 0;">
                <textarea class="staff-textarea-primary staff-textarea-large" placeholder="Description" required name="description" id="description"><?= $membershipPlan->description ?></textarea>
            </div>
            <div style="margin: 5px 0;">
                <input value="<?= $membershipPlan->price ?>" type="number" min="1" class="staff-input-primary staff-input-long" placeholder="Price" required name="price" id="price">
            </div>
            <div style="margin: 5px 0;">
                <input value="<?= $membershipPlan->duration ?>" type="number" min="1" class="staff-input-primary staff-input-long" placeholder="Duration in days" required name="duration" id="duration">
            </div>
            <input type="hidden" name="id" value="<?= $membershipPlan->id ?>">
<!--            <input value="--><?php //= $membershipPlan->name ?><!--" type="text" class="input" placeholder="Name" required name="name" id="name">-->
<!--            <textarea class="input" placeholder="Description" required name="description" id="description">--><?php //= $membershipPlan->description ?><!--</textarea>-->
<!--            <input value="--><?php //= $membershipPlan->price ?><!--" type="number" min="1" class="input" placeholder="Price" required name="price" id="price">-->
<!--            <input value="--><?php //= $membershipPlan->duration ?><!--" type="number" min="1" class="input" placeholder="Duration in days" required name="duration" id="duration">-->
<!--            <input type="hidden" name="id" value="--><?php //= $membershipPlan->id ?><!--">-->
<!--            <button class="btn">Save</button>-->
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>