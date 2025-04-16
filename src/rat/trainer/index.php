<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

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

$ratings_percentage = 4;
?>

<main>
    <img src="./avatar.webp" alt="Trainer Avatar" class="avatar">
    <h1 class="name">John Cena</h1>
    <p class="paragraph description">
        I am a fitness trainer with 10 years of experience.
        I specialize in strength training, weight loss and body building.
        <br>
        <br>
        <a href="tel:+94766743755">
            <u>+94766743755</u>
        </a>
    </p>
    <div class="ratings">
        <h2><?= $ratings_percentage ?>/5</h2>
        <div class="stars" style="--rating: <?= $ratings_percentage ?>;" title="Rating of this trainer is <?= $ratings_percentage ?> out of 5."></div>
        <p class="paragraph small">1,200 reviews</p>
    </div>
    <a href="./rate" class="btn">Rate Trainer</a>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>