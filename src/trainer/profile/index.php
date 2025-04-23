<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../db/models/Trainer.php";
require_once "../../uploads.php"; // Include the uploads helper

$trainer = new Trainer();
$trainer->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $trainer->get_by_id();
} catch (PDOException $e) {
    die("Failed to get trainer data due to error: " . $e->getMessage());
}

// Debug output to check what's stored in the database
error_log("Trainer avatar from database: " . $trainer->avatar);

// Use the get_file_url helper function if avatar exists
$avatar = $trainer->avatar
    ? get_file_url($trainer->avatar)
    : "/uploads/default-images/default-avatar.png";

// Debug the constructed path
error_log("Constructed avatar path: " . $avatar);

require_once "../../db/models/TrainerRating.php";
$trainerRating = new TrainerRating();
$rating = [
    'avg_rating' => 0,
    'review_count' => 0
];
try {
    $rating = $trainerRating->get_rating_of_trainer($trainer->id);
} catch (Exception $th) {
    die("Failed to get trainer rating: " . $th->getMessage());
}

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
        <!-- Force browser to refresh the image by adding timestamp -->
        <img src="<?= $avatar ?>?t=<?= time() ?>" alt="Profile Avatar" class="profile-avatar">

        <h1><?= $trainer->fname ?> <?= $trainer->lname ?></h1>

        <p>@<?= $trainer->username ?></p>
        <p class="profile-bio"><?= $trainer->bio ?></p>

        <div class="rating-section">
            <span class="rating-number"><?= number_format($rating['avg_rating'], 1) ?></span>
            <div class="rating-stars">
                ★★★★★
            </div>
            <span class="review-count">Out of <?= $rating['review_count'] ?> Reviews</span>
        </div>

        <a href="edit.php" class="btn full-width">EDIT PROFILE</a>
        <a href="../logout.php" class="btn secondary full-width">LOGOUT</a>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>