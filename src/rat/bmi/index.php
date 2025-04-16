<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$pageConfig = [
    "title" => "BMI Calculator",
    "styles" => ["./bmi.css"],
    "scripts" => ["./bmi.js"],
    "titlebar" => [
        "back_url" => "/rat/index.php",
    ],
    "navbar_active" => 1
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form action="result.php" method="post">
        <div class="height">
            <div class="line">
                <h4>Height</h4>
                <p>
                    <span class="value">175</span>
                    <span>cm</span>
                </p>
            </div>
            <input type="range" required name="height" min="100" value="175" max="300">
        </div>
        <div class="grid">
            <div class="number-input">
                <h4>Weight</h4>
                <div class="line">
                    <input value="70" required type="number" min="1" max="120" name="weight">
                    <span>KG</span>
                </div>
                <div class="btns">
                    <button data-target="weight" data-op="-">
                        -
                    </button>
                    <button data-target="weight" data-op="+">
                        +
                    </button>
                </div>
            </div>
            <div class="number-input">
                <h4>Age</h4>
                <div class="line">
                    <input value="24" required type="number" min="1" max="120" name="age">
                    <span>YRS</span>
                </div>
                <div class="btns">
                    <button data-target="age" data-op="-">
                        -
                    </button>
                    <button data-target="age" data-op="+">
                        +
                    </button>
                </div>
            </div>
        </div>
        <button class="btn">Calculate</button>
    </form>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>