<?php
$pageConfig = [
    "title" => "Post Announcement",
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form class="form" action="post_announcement_process.php" method="post">
        <div class="field">
            <input type="text" placeholder="Title" class="input" name="title" required>
        </div>
        <div class="field">
            <textarea class="input" name="message" required minlength="10" placeholder="Announcement"></textarea>
        </div>
        <div class="field">
            <label for="valid_till">Valid Till</label>
            <input type="date" id="valid_till" class="input" name="valid_till" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
        </div>
        <button class="btn">Post</button>
    </form>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>