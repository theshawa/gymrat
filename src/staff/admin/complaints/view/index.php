<?php
$id = $_GET['id'] ?? null;

$sidebarActive = 4;

require_once "../../../../db/models/Complaint.php";
require_once "../../../../alerts/functions.php";

$complaint = new Complaint();
try {
    $complaint->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch complaint: " . $e->getMessage(), "/staff/wnmp/exercises");
}
$_SESSION['complaint'] = $complaint;

$menuBarConfig = [
    "title" => "Complaint #" . $complaint->id,
    "showBack" => true,
    "goBackTo" => "/staff/admin/complaints/index.php",
];


require_once "../../pageconfig.php";


require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard_with_role("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="base-sub-container">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $complaint->description?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
