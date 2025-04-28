<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../db/models/Complaint.php";
$complaint = new Complaint();

try {
    $complaints = $complaint->get_all_of_user($_SESSION['auth']['id'], $_SESSION['auth']['role']);
} catch (\Throwable $th) {
    die("Failed to load complaints due to an error:" . $th->getMessage());
}

$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "../",
        "title" => "MAKE COMPLAINT"
    ],
    "styles" => ["./complaint.css"],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

function formatComplaintDescription($description) {
    // Check if the description is a JSON string
    $decoded = json_decode($description, true);
    
    // If not valid JSON or not a customer report, return the original description
    if (!$decoded || !isset($decoded['type']) || $decoded['type'] !== 'CUSTOMER REPORT') {
        return htmlspecialchars($description);
    }
    
    // Format customer report in a readable way
    $output = '<div class="customer-report">';
    
    if (isset($decoded['customer_id'])) {
        // Get customer name from the database using direct query
        $customerName = "Unknown Customer";
        try {
            require_once "../../db/Database.php";
            $conn = Database::get_conn();
            $sql = "SELECT fname, lname FROM customers WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $decoded['customer_id']);
            $stmt->execute();
            
            $customerData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($customerData) {
                $customerName = htmlspecialchars($customerData['fname'] . ' ' . $customerData['lname']);
            }
        } catch (Exception $e) {
            error_log("Error fetching customer name: " . $e->getMessage());
        }
        
        $output .= '<div class="report-field"><span class="label">Customer:</span> ' . $customerName . '</div>';
    }
    
    // Rest of the function remains the same
    if (isset($decoded['severity'])) {
        $severityClass = 'severity-' . htmlspecialchars($decoded['severity']);
        $output .= '<div class="report-field"><span class="label">Severity:</span> <span class="' . $severityClass . '">' . ucfirst(htmlspecialchars($decoded['severity'])) . '</span></div>';
    }
    
    if (isset($decoded['description'])) {
        $output .= '<div class="report-field"><span class="label">Details:</span> ' . htmlspecialchars($decoded['description']) . '</div>';
    }
    
    $output .= '</div>';
    return $output;
}
?>

<main>
    <!-- Complaint submission form -->
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Technical Issue">Technical Issue</option>
                <option value="Billing Problem">Billing Problem</option>
                <option value="Facility Feedback">Facility Feedback</option>
                <option value="Staff Feedback">Staff Feedback</option>
                <option value="Suggestion">Suggestion</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>
        <button class="btn">Submit Complaint</button>
        
        <div class="chart-info" style="margin: 20px 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
            <span style="padding-left: 7px">You can only edit or delete your complaints <br>within 5 minutes after posting!</span>
        </div>
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
                    $reviewed = $complaint->reviewed_at !== null; ?>
                    <li class="complaint-item">
                        <div class="inline">
                            <span class="paragraph small">
                                <?= format_time($complaint->created_at) ?>
                            </span>
                            
                            <?php
                            // Check if complaint is within 5-minute edit window
                            $now = new DateTime();
                            $created = $complaint->created_at;
                            $interval = $created->diff($now);
                            $totalMinutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);
                            $isEditable = $totalMinutes < 5;
                            
                            if ($isEditable): 
                            ?>
                            <div class="action-buttons">
                                <a href="./edit?id=<?= $complaint->id ?>" class="action-button edit-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.83 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </a>
                                <button class="action-button delete-button" onclick="delete_<?= $complaint->id ?>()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                            <?php endif; ?>
                            
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
                        <div class="complaint-content">
                            <?= formatComplaintDescription($complaint->description) ?>
                        </div>
                        <div class="review-message <?= $reviewed ? "reviewed" : "pending" ?>">
                            <div class="review-status <?= $reviewed ? "reviewed" : "pending" ?>">
                                <?= $reviewed ? "Reviewed by admin at " . format_time($complaint->reviewed_at) : "To be reviewed" ?>
                            </div>
                            <?php if ($reviewed): ?>
                                <p class="paragraph"><?= $complaint->review_message ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<style>
/* Additional styles for formatted customer reports */
.customer-report {
    background-color: rgba(103, 0, 230, 0.05);
    border-radius: 8px;
    padding: 12px;
    margin: 8px 0;
}

.report-field {
    margin-bottom: 8px;
}

.report-field .label {
    font-weight: 500;
    color: var(--color-zinc-300);
}

.report-field .severity-high {
    color: var(--color-red-light);
    font-weight: 500;
}

.report-field .severity-medium {
    color: var(--color-amber-light);
    font-weight: 500;
}

.report-field .severity-low {
    color: var(--color-blue-light);
    font-weight: 500;
}
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?> 