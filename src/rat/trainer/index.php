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

        <?php
        // echo '<br><br><a href="tel:' . $trainer->phone . '">
        //     <u>' . $trainer->phone . '</u>
        // </a>';
        ?>
    </p>
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