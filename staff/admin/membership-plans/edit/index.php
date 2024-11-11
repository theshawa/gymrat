<?php

$pageTitle = "Edit Membership Plan";
$pageStyles = ["../membership-plans.css"];

require_once "../../pageconfig.php";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../db/models/MembershipPlan.php";

$id = $_GET["id"];
$membershipPlan = new MembershipPlan();
$membershipPlan->get_by_id($id);


?>

<main>
    <h1>Edit Membership Plan: <?= $membershipPlan->name ?></h1>
    <?php if ($membershipPlan->is_locked): ?>
        <p class="paragraph">
            This plan is locked. New customers will not be able to see the changes you make.
        </p>
    <?php endif; ?>
    <form action="edit_process.php" method="post" class="form">
        <input value="<?= $membershipPlan->name ?>" type="text" class="input" placeholder="Name" required name="name" id="name">
        <textarea class="input" placeholder="Description" required name="description" id="description"><?= $membershipPlan->description ?></textarea>
        <input value="<?= $membershipPlan->price ?>" type="number" min="1" class="input" placeholder="Price" required name="price" id="price">
        <input value="<?= $membershipPlan->duration ?>" type="number" min="1" class="input" placeholder="Duration in days" required name="duration" id="duration">
        <input type="hidden" name="id" value="<?= $membershipPlan->id ?>">
        <button class="btn">Save</button>
    </form>
</main>

<?php require_once "../../../includes/footer.php"; ?>