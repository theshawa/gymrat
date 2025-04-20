<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../db/models/MembershipPlan.php";
require_once "../../../alerts/functions.php";

$pageTitle = "Membership Plans";
$sidebarActive = 2;
$pageStyles = ["./membership-plans.css"];
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create New", "href" => "/staff/admin/membership-plans/new/index.php", "type" => "secondary"]
    ]
];

$membershipPlans = [];
$membershipPlanModel = new MembershipPlan();
try {
    $membershipPlans = $membershipPlanModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch membership plans: " . $e->getMessage(), "/staff/admin");
}

require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <!--    <a href="new" class="btn" style="width: max-content;">Create New</button></a>-->
        <!--        <br />-->
        <p class="paragraph">
            You can lock plans to hide them from customers. Plan will be hidden from the membership plan list in the customer onboarding view. This is useful when you want to edit or delete them in the future.
        </p>
        <br />
        <div class="card-list">
            <?php foreach ($membershipPlans as $membershipPlan) : ?>
                <div class="card">
                    <?php
                    require_once "../../../db/models/Customer.php";
                    $customers = (new Customer())->get_all_by_membership_plan_id($membershipPlan->id);
                    ?>
                    <h2><?= $membershipPlan->name . ($membershipPlan->is_locked ? "&nbsp;<strong>[LOCKED]</strong>" : "") ?></h2>
                    <p style="font-weight: 500;"><?= $membershipPlan->description ?></p>
                    <p>Price: <?= $membershipPlan->price ?> LKR</p>
                    <p>Duration: <?= $membershipPlan->duration ?> days</p>
                    <p>Created at: <?= $membershipPlan->created_at->format('Y-m-d H:i:s') ?></p>
                    <p>Updated at: <?= $membershipPlan->updated_at->format('Y-m-d H:i:s') ?></p>
                    <p>No. of active users: <?= count($customers) ?></p>
                    <div class="btns">
                        <?php if (count($customers)): ?>
                            <button class="btn" disabled title="There are active customers">Edit</button>
                            <button class="btn" disabled title="There are active customers">Delete</button>
                        <?php else: ?>
                            <a <?= count($customers) ? 'disabled title="Active customers exists"' : "" ?> href="edit/index.php?id=<?= $membershipPlan->id ?>" class="btn">Edit</a>
                            <!-- TODO: Hide below buttons based on currently active customers -->

                            <button <?= count($customers) ? 'disabled title="Active customers exists"' : "" ?> onclick="deletePlan(<?= $membershipPlan->id ?>)" class="btn">Delete</button>
                        <?php endif; ?>
                        <button onclick="lockUnlockPlan(<?= $membershipPlan->id ?>,<?= $membershipPlan->is_locked ? 0 : 1 ?>)" class="btn"><?= $membershipPlan->is_locked ? "Unlock" : "Lock" ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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