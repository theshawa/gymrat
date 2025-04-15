<?php
$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "../",
        "title" => "MAKE COMPLAINT"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
require_once "../../db/models/Complaint.php";

// Get trainer ID from session
$trainerId = $_SESSION['auth']['id'] ?? 0;

// Get database connection
$conn = Database::get_conn();

// Fetch complaints made by this trainer
$complaints = [];
try {
    $sql = "SELECT * FROM complaints 
            WHERE user_id = :trainer_id 
            AND is_created_by_trainer = 1 
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':trainer_id', $trainerId);
    $stmt->execute();

    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle error silently
}
?>

<main>
    <!-- Complaint submission form -->
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Inappropriate Behavior">Inappropriate Behavior</option>
                <option value="Equipment Misuse">Equipment Misuse</option>
                <option value="Attendance Problem">Attendance Problem</option>
                <option value="Policy Violation">Policy Violation</option>
                <option value="Hygiene Concern">Hygiene Concern</option>
                <option value="Other Issue">Other Issue</option>
            </select>
        </div>
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>
        <button class="btn">Submit Complaint</button>
    </form>

    <!-- Previous reports section -->
    <?php if (!empty($complaints)): ?>
        <h2 class="previous-reports-title">PREVIOUS COMPLAINTS</h2>

        <div class="reports-list">
            <?php foreach ($complaints as $complaint): ?>
                <div class="report-item">
                    <div class="report-header">
                        <h3><?= htmlspecialchars($complaint['type']) ?></h3>
                        <span
                            class="report-status <?= isset($complaint['status']) && $complaint['status'] == 'reviewed' ? 'status-reviewed' : 'status-pending' ?>">
                            <?= isset($complaint['status']) && $complaint['status'] == 'reviewed' ? 'Reviewed' : 'Pending' ?>
                        </span>
                    </div>

                    <p class="report-description"><?= htmlspecialchars($complaint['description']) ?></p>

                    <div class="report-footer">
                        <span class="report-date">
                            <?= date('M d, Y', strtotime($complaint['created_at'])) ?>
                        </span>
                    </div>

                    <?php if (isset($complaint['admin_reply']) && !empty($complaint['admin_reply'])): ?>
                        <div class="admin-reply">
                            <div class="reply-header">
                                <span class="admin-label">Admin Response</span>
                                <?php if (isset($complaint['replied_at']) && !empty($complaint['replied_at'])): ?>
                                    <span class="reply-date">
                                        <?= date('M d, Y', strtotime($complaint['replied_at'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p><?= htmlspecialchars($complaint['admin_reply']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<style>
    main {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .previous-reports-title {
        margin-top: 10px;
        font-size: 20px;
        font-weight: 600;
    }

    .reports-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .report-item {
        background-color: var(--color-zinc-900);
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid var(--color-violet-600);
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .report-header h3 {
        margin: 0;
        font-size: 16px;
        text-transform: none;
        letter-spacing: normal;
    }

    .report-status {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 12px;
    }

    .status-pending {
        background-color: var(--color-zinc-800);
        color: var(--color-zinc-400);
    }

    .status-reviewed {
        background-color: var(--color-green);
        color: var(--color-zinc-50);
    }

    .report-description {
        font-size: 14px;
        color: var(--color-zinc-400);
        margin: 10px 0;
        line-height: 1.5;
    }

    .report-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--color-zinc-800);
    }

    .report-date {
        font-size: 12px;
        color: var(--color-zinc-500);
    }

    .admin-reply {
        background-color: var(--color-zinc-800);
        padding: 12px;
        margin-top: 10px;
        border-radius: 8px;
    }

    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .admin-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-green-light);
    }

    .reply-date {
        font-size: 11px;
        color: var(--color-zinc-500);
    }

    .admin-reply p {
        margin: 0;
        color: var(--color-zinc-300);
        font-size: 13px;
        line-height: 1.5;
    }
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>