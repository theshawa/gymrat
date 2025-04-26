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

// Use the get_file_url helper function if avatar exists
$avatar = get_file_url($trainer->avatar, "/default-images/default-avatar.png");

// Add cache buster from session if available
$cache_buster = isset($_SESSION['cache_buster']) ? $_SESSION['cache_buster'] : md5(time());

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
        <!-- Force browser to refresh the image using cache buster -->
        <img src="<?= $avatar ?>?v=<?= $cache_buster ?>" alt="Profile Avatar" class="profile-avatar" id="profile-image">

        <h1><?= $trainer->fname ?> <?= $trainer->lname ?></h1>

        <p>@<?= $trainer->username ?></p>
        <p class="profile-bio"><?= $trainer->bio ?></p>

        <div class="rating-section">
            <span class="rating-number">
                <?= number_format($rating['avg_rating'], 1) ?>
                <span style="font-size: 18px;">/ 5</span>
            </span>
            
            <div class="star-rating">
                <?php
                // Calculate full stars, partial stars, and empty stars
                $fullStars = floor($rating['avg_rating']);
                $partialStar = $rating['avg_rating'] - $fullStars > 0;
                $partialStarPercentage = ($rating['avg_rating'] - $fullStars) * 100;
                $emptyStars = 5 - $fullStars - ($partialStar ? 1 : 0);
                
                // Output full stars
                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<span class="star full">★</span>';
                }
                
                // Output partial star if needed
                if ($partialStar) {
                    echo '<div class="star-partial-container">';
                    echo '<span class="star-empty">☆</span>';
                    echo '<span class="star-filled" style="width: ' . $partialStarPercentage . '%;">★</span>';
                    echo '</div>';
                }
                
                // Output empty stars
                for ($i = 0; $i < $emptyStars; $i++) {
                    echo '<span class="star empty">☆</span>';
                }
                ?>
            </div>
            
            <span class="review-count">Out of <?= $rating['review_count'] ?> Reviews</span>
        </div>

        <a href="edit.php" class="btn full-width">EDIT PROFILE</a>
        <a href="../logout.php" class="btn secondary full-width">LOGOUT</a>
    </div>
</main>

<style>
/* Enhanced star rating styling */
.rating-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #18181B;
    border-radius: 20px;
    padding: 20px;
    width: 100%;
    margin: 10px 0;
}

.rating-number {
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 5px;
    color:rgb(255, 255, 255);
}

.star-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 10px 0;
}

.star {
    font-size: 20px;
    line-height: 1;
}

.star.full {
    color: #ffe100;
}

.star.empty {
    color: #444;
}

/* Partial star styling */
.star-partial-container {
    position: relative;
    display: inline-block;
    font-size: 20px;
    line-height: 1;
    height: 20px;
}

.star-empty {
    color: #444;
}

.star-filled {
    position: absolute;
    top: 0;
    left: 0;
    color: #ffe100;
    overflow: hidden;
    height: 100%;
    white-space: nowrap;
}

.review-count {
    font-size: 14px;
    color: #a1a1aa;
    margin-top: 5px;
}
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>