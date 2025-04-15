<?php
// This file should be placed in the admin section
// /admin/complaints/view_complaint.php

$pageConfig = [
    "title" => "View Complaint",
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "./",
        "title" => "TRAINER COMPLAINT"
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
require_once "../../db/models/Complaint.php";

// Get complaint ID from URL
$complaintId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($complaintId <= 0) {
    redirect_with_error_alert("Invalid complaint ID", "./");
}

// Get database connection
$conn = Database::get_conn();

// Fetch the complaint
$complaint = null;
try {
    $sql = "SELECT * FROM complaints WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $complaintId);
    $stmt->execute();

    $complaint = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$complaint) {
        redirect_with_error_alert("Complaint not found", "./");
    }

    // Get trainer info
    $trainerSql = "SELECT fname, lname FROM trainers WHERE id = :id";
    $trainerStmt = $conn->prepare($trainerSql);
    $trainerStmt->bindValue(':id', $complaint['user_id']);
    $trainerStmt->execute();

    $trainerInfo = $trainerStmt->fetch(PDO::FETCH_ASSOC);
    $trainerName = $trainerInfo ? $trainerInfo['fname'] . ' ' . $trainerInfo['lname'] : 'Unknown Trainer';

    // Process complaint description to extract severity if present
    if (preg_match('/^\[([a-zA-Z]+)\]:\s*(.*)$/s', $complaint['description'], $matches)) {
        $complaint['severity'] = strtolower($matches[1]);
        $complaint['clean_description'] = $matches[2];
    } else {
        $complaint['severity'] = 'medium'; // Default
        $complaint['clean_description'] = $complaint['description'];
    }

} catch (Exception $e) {
    redirect_with_error_alert("Error fetching complaint: " . $e->getMessage(), "./");
}
?>

<main>
    <div class="complaint-details">
        <!-- Complaint header info -->
        <div class="complaint-header">
            <h2><?= htmlspecialchars($complaint['type']) ?></h2>
            <div class="meta-info">
                <div class="trainer-info">
                    <span class="label">Submitted by:</span>
                    <span class="value"><?= htmlspecialchars($trainerName) ?></span>
                </div>
                <div class="date-info">
                    <span class="label">Date:</span>
                    <span class="value"><?= date('M d, Y', strtotime($complaint['created_at'])) ?></span>
                </div>
                <?php if (isset($complaint['severity'])): ?>
                    <div class="severity-info">
                        <span class="label">Severity:</span>
                        <span
                            class="value severity-<?= $complaint['severity'] ?>"><?= ucfirst($complaint['severity']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Complaint description -->
        <div class="complaint-content">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($complaint['clean_description'] ?? $complaint['description'])) ?></p>
        </div>

        <!-- Existing admin reply if any -->
        <?php if (!empty($complaint['admin_reply'])): ?>
            <div class="admin-response">
                <h3>Your Previous Response</h3>
                <p><?= nl2br(htmlspecialchars($complaint['admin_reply'])) ?></p>
                <div class="response-meta">
                    <span class="response-date">Replied on:
                        <?= date('M d, Y', strtotime($complaint['replied_at'])) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Admin reply form -->
        <?php if (empty($complaint['admin_reply'])): ?>
            <div class="reply-form">
                <h3>Respond to Complaint</h3>
                <form action="admin_reply_complaint.php" method="post">
                    <input type="hidden" name="complaint_id" value="<?= $complaintId ?>">

                    <div class="form-field">
                        <label for="status">Set Status:</label>
                        <select id="status" name="status" class="form-select">
                            <option value="reviewed">Reviewed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="admin_reply">Your Response:</label>
                        <textarea id="admin_reply" name="admin_reply" rows="5" required class="form-textarea"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Send Response</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="update-response">
                <h3>Update Response</h3>
                <form action="admin_update_reply.php" method="post">
                    <input type="hidden" name="complaint_id" value="<?= $complaintId ?>">

                    <div class="form-field">
                        <label for="status">Update Status:</label>
                        <select id="status" name="status" class="form-select">
                            <option value="reviewed" <?= $complaint['status'] == 'reviewed' ? 'selected' : '' ?>>Reviewed
                            </option>
                            <option value="in_progress" <?= $complaint['status'] == 'in_progress' ? 'selected' : '' ?>>In
                                Progress</option>
                            <option value="resolved" <?= $complaint['status'] == 'resolved' ? 'selected' : '' ?>>Resolved
                            </option>
                            <option value="dismissed" <?= $complaint['status'] == 'dismissed' ? 'selected' : '' ?>>Dismissed
                            </option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="admin_reply">Update Response:</label>
                        <textarea id="admin_reply" name="admin_reply" rows="5" required
                            class="form-textarea"><?= htmlspecialchars($complaint['admin_reply']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Response</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
    .complaint-details {
        background-color: #18181B;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .complaint-header {
        margin-bottom: 20px;
    }

    .complaint-header h2 {
        margin-bottom: 10px;
    }

    .meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
    }

    .meta-info>div {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .label {
        font-size: 12px;
        color: #a1a1aa;
    }

    .value {
        font-size: 14px;
        font-weight: 500;
    }

    .severity-high {
        color: #ff4d4d;
    }

    .severity-medium {
        color: #ff9800;
    }

    .severity-low {
        color: #ffcd56;
    }

    .complaint-content,
    .admin-response {
        background-color: #27272A;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .complaint-content h3,
    .admin-response h3,
    .reply-form h3,
    .update-response h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .admin-response {
        border-left: 4px solid #4CAF50;
    }

    .response-meta {
        margin-top: 10px;
        font-size: 12px;
        color: #a1a1aa;
        text-align: right;
    }

    .form-field {
        margin-bottom: 15px;
    }

    .form-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-select,
    .form-textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #3f3f46;
        background-color: #27272A;
        color: #e4e4e7;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background-color: #6700E6;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #5b00cc;
    }
</style>

<?php require_once "../includes/footer.php" ?>