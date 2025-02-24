<?php

$pageTitle = "Create Membership Plan";
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
auth_required_guard("admin", "/staff/login");

?>

<main>
    <div class="staff-base-container">
        <!--        <form action="create_process.php" method="post" class="form">-->
        <form action="create_process.php" method="post" class="form alt">
            <?php require_once "../../../includes/menubar.php"; ?>
            <input type="text" class="staff-input-primary staff-input-long" placeholder="Name" required name="name" id="name">
            <textarea class="staff-textarea-primary staff-textarea-large" placeholder="Description" required name="description" id="description"></textarea>
            <input type="number" min="1" class="staff-input-primary staff-input-long" placeholder="Price" required name="price" id="price">
            <input type="number" min="1" class="staff-input-primary staff-input-long" placeholder="Duration in days" required name="duration" id="duration">
            <!--            <input type="text" class="input" placeholder="Name" required name="name" id="name">-->
            <!--            <textarea class="input" placeholder="Description" required name="description" id="description"></textarea>-->
            <!--            <input type="number" min="1" class="input" placeholder="Price" required name="price" id="price">-->
            <!--            <input type="number" min="1" class="input" placeholder="Duration in days" required name="duration" id="duration">-->
            <!--            <button class="btn">Create</button>-->
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>