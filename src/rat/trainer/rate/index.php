<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$pageConfig = [
    "title" => "Rate My Trainer",
    "styles" => ["../trainer.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main>
    <form action="rate_process.php" method="post" class="form">
        <select required name="rate" id="" class="input">
            <option value="" disabled selected>Select Rating</option>
            <option value="5">Excellent</option>
            <option value="4">Good</option>
            <option value="3">Average</option>
            <option value="2">Poor</option>
            <option value="1">Terrible</option>
        </select>
        <textarea required type="text" placeholder="Describe your experience" name="review" class="input"></textarea>
        <button class="btn">Rate</button>
    </form>

</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>