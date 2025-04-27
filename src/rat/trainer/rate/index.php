<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../../db/models/TrainerRating.php";

try {
    $ratings = (new TrainerRating())->get_all_of_user($_SESSION['auth']['id']);
} catch (\Throwable $th) {
    die("Failed to get ratings: " . $th->getMessage());
}

$rating_status = [
    'Terrible',
    'Poor',
    'Average',
    'Good',
    'Excellent'
];

$pageConfig = [
    "title" => "Rate My Trainer",
    "styles" => ["../trainer.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main>
    <form action="rate_process.php" method="post" class="form">
        <select required name="rate" id="" class="input">
            <option value="" disabled selected>Select Rating</option>
            <?php foreach ($rating_status as $index => $status): ?>
                <option value="<?= $index + 1 ?>"><?= $status ?></option>
            <?php endforeach; ?>
        </select>
        <textarea required type="text" placeholder="Describe your experience" name="review" class="input"></textarea>
        <button class="btn">Rate</button>
    </form>
    <div class="rating-list">
        <h3>Previous Ratings</h3>
        <?php if (count($ratings) == 0): ?>
            <p class="no-ratings">No ratings yet.</p>
        <?php else: ?>
            <?php require_once "../../../utils.php"; ?>
            <?php foreach ($ratings as $rating): ?>
                <div class="rating-item">
                    <div class="top">
                        <span class="time"><?= format_time($rating->created_at, true) ?></span>
                        <button onclick="deleteItem(<?= $rating->id ?>)" class="delete-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                        </button>
                    </div>
                    <span class="status"><?= $rating_status[$rating->rating - 1] ?></span>
                    <p class="review"><?= $rating->review ?></p>
                </div>
            <?php endforeach; ?>
            <script>
                const deleteItem = (id) => {
                    if (confirm("Are you sure you want to delete this rating?")) {
                        const form = document.createElement("form");
                        form.method = "POST";
                        form.action = "./delete_rating_process.php";
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
    </div>

</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>