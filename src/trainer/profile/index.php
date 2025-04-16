<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../db/models/Trainer.php";

$trainer = new Trainer();
$trainer->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $trainer->get_by_id();
} catch (PDOException $e) {
    die("Failed to get trainer data due to error: " . $e->getMessage());
}

$avatar = $trainer->avatar ? "/uploads/" . $trainer->avatar : "/uploads/default-images/default-avatar.png";

$pageConfig = [
    "title" => "My Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css"]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <div class="profile-content">
        <img src="<?= $avatar ?>" alt="" class="profile-avatar">

        <h1><?= $trainer->fname ?> <?= $trainer->lname ?></h1>

        <p>@<?= $trainer->username ?></p>
        <p class="profile-bio"><?= $trainer->bio ?></p>

        <div class="rating-section">
            <span class="rating-number"><?= $trainer->rating ?></span>
            <div class="rating-stars">
                ★★★★★
            </div>
            <span class="review-count">Out of <?= $trainer->review_count ?> Reviews</span>
        </div>

        <a href="edit.php" class="btn full-width">EDIT PROFILE</a>
        <a href="../logout.php" class="btn secondary full-width">LOGOUT</a>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>