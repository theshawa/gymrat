<?php

session_start();

$complaints = [];
require_once "../../db/models/Complaint.php";
require_once "../../alerts/functions.php";

try {
    $complaint = new Complaint();
    $complaints = $complaint->get_all_of_user($_SESSION['auth']['id'], $_SESSION['auth']['role']);
} catch (Exception $e) {
    redirect_with_error_alert("An error occurred while loading complaint history: " . $e->getMessage(), "../");
}

$pageConfig = [
    "title" => "Make Complaint",
    "styles" => [
        "./complaint.css",
    ],
    "titlebar" => [
        "back_url" => "/rat/index.php",
    ],
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
            <textarea class="input" name="description" required placeholder="Description" maxlength="100"></textarea>
        </div>
        <button class="btn">Submit</button>
    </form>
    <div class="complaint-history">
        <h3>Complaint History</h3>
        <?php if (count($complaints) === 0): ?>
            <p>No complaints found</p>
        <?php else: ?>
            <ul class="complaint-list">
                <?php foreach ($complaints as $complaint): ?>
                    <li>
                        <div class="inline">
                            <span class="time">
                                <?= $complaint->created_at->format("F j, Y, g:i a") ?>
                            </span>
                            <?php if (!$complaint->reviewed_at) : ?>
                                <button class="delete-button" title="Delete this complain" onclick="deleteComplaint(<?= $complaint->id ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                        <h4 class="type">
                            <?= htmlspecialchars($complaint->type) ?>
                        </h4>
                        <p class="description">
                            <?= htmlspecialchars($complaint->description) ?>
                        </p>
                        <div class="review-message <?= $complaint->reviewed_at ? 'reviewed' : 'pending' ?>">
                            <?php if ($complaint->reviewed_at): ?>
                                <?php if ($complaint->reviewed_at): ?>
                                    <span class="review-status">
                                        Reviewed by admin at <?= $complaint->reviewed_at->format("F j, Y, g:i a") ?>
                                    </span>
                                <?php endif; ?>
                                <p>
                                    <?= htmlspecialchars($complaint->review_message) ?>
                                </p>
                            <?php else: ?>
                                <span class="review-status pending">To be reviewed</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <script>
                function deleteComplaint(id) {
                    if (confirm("Are you sure you want to delete this complaint?")) {
                        const form = document.createElement("form");
                        form.method = "POST";
                        form.action = "delete_complaint_process.php";
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "id";
                        input.value = id;
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            </script>
        <?php endif; ?>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>