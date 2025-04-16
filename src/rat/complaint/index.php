<?php

require_once "../../db/models/Complaint.php";
require_once "../../alerts/functions.php";
$complaint = new Complaint();

session_start();

try {
    $complaints = $complaint->get_all_of_user($_SESSION['auth']['id'], $_SESSION['auth']['role']);
} catch (\Throwable $th) {
    redirect_with_error_alert("Failed to load complaints due to an error:" . $th->getMessage(), "../");
}

$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "/rat/index.php",
    ],
    "styles" => ["./complaint.css"],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Facility Issues">Facility Issues</option>
                <option value="Staff Complaints">Staff Complaints</option>
                <option value="Membership Issues">Membership Issues</option>
                <option value="Trainer Performance">Trainer Performance</option>
                <option value="Service Quality">Service Quality</option>
                <option value="Health and Safety Concerns">Health and Safety Concerns</option>
                <option value="General Feedback">General Feedback</option>
            </select>
        </div>
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>
        <button class="btn">Submit</button>
    </form>
    <div class="complaint-history">
        <h3>Complaint History</h3>
        <?php if (empty($complaints)): ?>
            <p class="paragraph small">No complaints.</p>
        <?php else: ?>
            <ul class="complaint-list">
                <?php foreach ($complaints as $complaint): ?>
                    <?php
                    require_once "../../utils.php";
                    $reveiewed = $complaint->reviewed_at !== null;  ?>
                    <li class="complaint-item">
                        <div class="inline">
                            <span class="paragraph small">
                                <?= format_time($complaint->created_at) ?>
                            </span>
                            <button class="delete-button" onclick="delete_<?= $complaint->id ?>()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            <script>
                                function delete_<?= $complaint->id ?>() {
                                    if (confirm("Are you sure you want to delete this complaint?")) {
                                        const form = document.createElement("form");
                                        form.method = "POST";
                                        form.action = "delete_complaint_process.php";
                                        const input = document.createElement("input");
                                        input.type = "hidden";
                                        input.name = "id";
                                        input.value = <?= $complaint->id ?>;
                                        form.appendChild(input);
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                }
                            </script>
                        </div>
                        <h4 class="type"><?= htmlspecialchars($complaint->type) ?></h4>
                        <p class="paragraph"><?= htmlspecialchars($complaint->description) ?></p>
                        <div class="review-message <?= $reveiewed ? "reviewed" : "pending" ?>">
                            <div class="review-status <?= $reveiewed ? "reviewed" : "pending" ?>"><?= $reveiewed ? "Reviewed by admin at " . format_time($complaint->reviewed_at) : "To be reviewed" ?></div>
                            <?php if ($reveiewed): ?>
                                <p class="paragraph"><?= $complaint->review_message ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>