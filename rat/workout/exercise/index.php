<?php
$pageConfig = [
    "title" => "Exercise Info",
    "styles" => ["./exercise.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>

<main>
    <h1>Bench Press</h1>
    <span>8 x 4 reps</span>
    <p class="paragraph">Targets the chest, shoulders, and triceps.</p>
    <!-- <img src="" alt="" class="featured-image"> -->
    <div class="tutorial">
        <h3>TUTORIAL</h3>
        <iframe src="https://www.youtube.com/embed/4Y2ZdHCOXok" frameborder="0"></iframe>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>