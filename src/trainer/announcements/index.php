<?php
// File path: src/trainer/announcements/index.php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../db/models/Announcement.php";

// Get all announcements created by this trainer
$trainer_id = $_SESSION['auth']['id'];
$announcement = new Announcement();
$announcements = $announcement->get_all_of_source($trainer_id);

$pageConfig = [
    "title" => "Announcements",
    "styles" => [
        "./announcements.css"
    ],
    "navbar_active" => 2, // Keep the existing active nav item
    "titlebar" => [
        "back_url" => "../",
        "title" => "ANNOUNCEMENTS"
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "../../utils.php"; // For date formatting

// Calculate current time for edit time comparison
$current_time = new DateTime();
?>

<main>
    <!-- Announcement submission form -->
    <form class="form" action="../post-announcement/post_announcement_process.php" method="post">
        <div class="field">
            <input type="text" placeholder="Title" class="input" name="title" required>
        </div>
        <div class="field">
            <textarea class="input" name="message" required minlength="10" placeholder="Announcement Message"></textarea>
        </div>
        <div class="field">
            <label for="valid_till">Valid Till</label>
            <input type="date" id="valid_till" class="input" name="valid_till" required 
                   value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
        </div>
        <button class="btn">Post Announcement</button>
    </form>
    
    <!-- Add information box about 5-minute edit restriction -->
    <div class="chart-info" style="margin: 20px 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
        </svg>
        <span style="padding-left: 7px">You can only edit your announcements <br>within 5 minutes after posting!</span>
    </div>
    
    <div class="announcement-history">
        <h3>Announcement History</h3>
        <?php if (empty($announcements)): ?>
            <p class="paragraph small">No announcements.</p>
        <?php else: ?>
            <ul class="announcement-list">
                <?php foreach ($announcements as $announcement): 
                    // Calculate if the announcement was created less than 5 minutes ago
                    $edit_time_diff = $current_time->getTimestamp() - $announcement->created_at->getTimestamp();
                    $can_edit = $edit_time_diff <= 300; // 300 seconds = 5 minutes
                ?>
                    <li class="announcement-item">
                        <div class="inline">
                            <span class="paragraph small">
                                <?= format_time($announcement->created_at) ?>
                            </span>
                            <div class="action-buttons">
                                <?php if ($can_edit): ?>
                                <a href="./edit/?id=<?= $announcement->id ?>" class="action-button edit-button">
                                    <!-- Edit Icon SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        <path d="m15 5 4 4"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                <button class="action-button delete-button" onclick="delete_<?= $announcement->id ?>()">
                                    <!-- Delete Icon SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M3 6h18"/>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </button>
                            </div>
                            <script>
                                function delete_<?= $announcement->id ?>() {
                                    if (confirm("Are you sure you want to delete this announcement?")) {
                                        const form = document.createElement("form");
                                        form.method = "POST";
                                        form.action = "delete/delete_announcement_process.php";
                                        const input = document.createElement("input");
                                        input.type = "hidden";
                                        input.name = "id";
                                        input.value = <?= $announcement->id ?>;
                                        form.appendChild(input);
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                }
                            </script>
                        </div>
                        <h4 class="title"><?= htmlspecialchars($announcement->title) ?></h4>
                        <p class="paragraph message"><?= htmlspecialchars($announcement->message) ?></p>
                        <div class="meta">
                            <span>Valid until: <?= $announcement->valid_till->format('M d, Y') ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>