<?php
$pageConfig = [
    "title" => "Exercise",
    "styles" => ["./exercise.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>

<main>
    <h1>Bench Press</h1>
    <span>8 x 4 reps</span>
    <p class="paragraph">Targets the chest, shoulders, and triceps.</p>
    <iframe src="https://www.youtube.com/embed/a3ICNMQW7Ok" frameborder="0"></iframe>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>