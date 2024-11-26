<?php

$pageTitle = "Create Membership Plan";
$pageStyles = ["../membership-plans.css"];

require_once "../../pageconfig.php";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard_with_role("admin", "/staff/login");

?>

<main>
    <h1>New Membership Plan</h1>
    <form action="create_process.php" method="post" class="form">
        <input type="text" class="input" placeholder="Name" required name="name" id="name">
        <textarea class="input" placeholder="Description" required name="description" id="description"></textarea>
        <input type="number" min="1" class="input" placeholder="Price" required name="price" id="price">
        <input type="number" min="1" class="input" placeholder="Duration in days" required name="duration" id="duration">
        <button class="btn">Create</button>
    </form>
</main>

<?php require_once "../../../includes/footer.php"; ?>