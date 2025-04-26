<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Customer.php";
$customer = new Customer();
$customer->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $customer->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

require_once "../../db/models/Trainer.php";
$trainer = new Trainer();
$trainer->fill([
    'id' => $customer->trainer
]);
try {
    $trainer->get_by_id();
} catch (Exception $th) {
    die("Failed to get trainer: " . $th->getMessage());
}

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


$avatar = $trainer->avatar ? "/uploads/" . $trainer->avatar : "/uploads/default-images/default-avatar.png";
$pageConfig = [
    "title" => "My Trainer",
    "styles" => ["./trainer.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<main>
    <img src="<?= $avatar ?>" alt="Trainer Avatar" class="avatar">
    <h1 class="name"><?= $trainer->fname . " " . $trainer->lname ?></h1>
    <p class="paragraph description">
        <?= $trainer->bio ?>


    </p>
    <a class="phone" href="tel:<?= $trainer->phone ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-icon lucide-phone">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
        </svg>
        <span><?= $trainer->phone ?></span>
    </a>
    <div class="ratings">
        <p class="paragraph small">RATING</p>
        <h2><?= number_format($rating['avg_rating'], 1) ?></h2>
        <div class="stars" style="--rating: <?= $rating['avg_rating'] ?>;" title="Rating of this trainer is <?= $rating['avg_rating'] ?> out of 5."></div>
        <!-- <p class="paragraph small"><?= $rating['review_count'] === 0 ? "No" : $rating['review_count'] ?> reviews</p> -->
    </div>
    <a href="./rate" class="btn">Rate Trainer</a>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>