<?php
$pageConfig = [
    "title" => "My Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css"],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "../../db/models/Trainer.php";

$trainer = new Trainer();
$trainer->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $trainer->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get trainer data: " . $e->getMessage(), "./");
}

$avatar = $trainer->avatar ? "/uploads/" . $trainer->avatar : "/uploads/default-images/default-avatar.png";

// Include the alert view
require_once "../../alerts/view.php";
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

        <a href="edit.php" class="btn btn-edit">EDIT PROFILE</a>
        <a href="../logout.php" class="btn btn-logout">LOGOUT</a>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>