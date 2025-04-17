<?php
$id = $_GET['id'] ?? null;

$sidebarActive = 4;

require_once "../../../../db/models/Complaint.php";
require_once "../../../../alerts/functions.php";

$complaint = new Complaint();
try {
    $complaint->get_by_id($id);
    $complaint->get_username();
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
auth_required_guard("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="staff-base-sub-container-alt">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $complaint->description ?></p>
                <div style="display: flex; flex-direction: row; margin-top: 20px; align-items: center; ">
                    <?php if($complaint->user_type === "trainer"): ?>
                        <h2>
                            Trainer : 
                        </h2>
                        <p>&emsp;<?= $complaint->user_name ?></p>
                    <?php elseif ($complaint->user_type === "rat"): ?>
                        <h2>
                            Rat : 
                        </h2>
                        <p>&emsp;<?= $complaint->user_name ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!($complaint->review_message)): ?>
            <form action="review_complaint.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <input type="hidden" name="id" value="<?= $complaint->id ?>">
                <h2>
                    Review Complaint
                </h2>
                <p>Write response message to review to complaint</p>
                <textarea id="review" name="review_message"
                        class="staff-textarea-primary staff-textarea-large"
                        placeholder="Enter review message"><?= $complaint->review_message ?></textarea>
                <button type="submit" class="staff-button secondary" style="min-height: 38px; min-width:120px; margin-top: 5px;">Confirm</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>