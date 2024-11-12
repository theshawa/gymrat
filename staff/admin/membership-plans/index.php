<?php

$pageTitle = "Membership Plans";
$sidebarActive = 2;
$pageStyles = ["./membership-plans.css"];

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../db/models/MembershipPlan.php";

$membershipPlanModel = new MembershipPlan();
$membershipPlans = $membershipPlanModel->get_all();
?>

<main>
    <a href="new" class="btn" style="width: max-content;">Create New</button></a>
    <br />
    <p class="paragraph">
        You can lock plans to hide them from customers. Plan will be hidden from the membership plan list in the customer onboarding view. This is useful when you want to edit or delete them in the future.
    </p>
    <br />
    <div class="card-list">
        <?php foreach ($membershipPlans as $membershipPlan) : ?>
            <div class="card">
                <h2><?= $membershipPlan->name . ($membershipPlan->is_locked ? "&nbsp;<strong>[LOCKED]</strong>" : "") ?></h2>
                <p style="font-weight: 500;"><?= $membershipPlan->description ?></p>
                <p>Price: <?= $membershipPlan->price ?> LKR</p>
                <p>Duration: <?= $membershipPlan->duration ?> days</p>
                <p>Created at: <?= $membershipPlan->created_at->format('Y-m-d H:i:s') ?></p>
                <p>Updated at: <?= $membershipPlan->updated_at->format('Y-m-d H:i:s') ?></p>
                <div class="btns">
                    <a href="edit/index.php?id=<?= $membershipPlan->id ?>" class="btn">Edit</a>
                    <!-- TODO: Hide below buttons based on currently active customers -->
                    <button onclick="deletePlan(<?= $membershipPlan->id ?>)" class="btn">Delete</button>
                    <button onclick="lockUnlockPlan(<?= $membershipPlan->id ?>,<?= $membershipPlan->is_locked ? 0 : 1 ?>)" class="btn"><?= $membershipPlan->is_locked ? "Unlock" : "Lock" ?></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
    const deletePlan = (id) => {
        if (confirm("Are you sure you want to delete this membership plan?")) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "delete.php";
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "id";
            input.value = id;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    const lockUnlockPlan = (id, status) => {
        if (confirm(`Are you sure you want to ${status ? "lock" : "unlock"} this membership plan? ${status ? "Customers will not be able to see this plan." : "Customers will be able to see this plan."}`)) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "lock-unlock.php";
            const input1 = document.createElement("input");
            input1.type = "hidden";
            input1.name = "id";
            input1.value = id;
            form.appendChild(input1);
            const input2 = document.createElement("input");
            input2.type = "hidden";
            input2.name = "status";
            input2.value = status;
            form.appendChild(input2);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once "../../includes/footer.php"; ?>