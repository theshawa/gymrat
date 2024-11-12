<?php
$pageConfig = [
    "title" => "BMI Calculator",
    "styles" => ["./bmi.css"],
    "scripts" => ["./bmi.js"],
    "titlebar" => [
        "back_url" => "/rat/index.php",
    ],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form action="result.php" method="post">
        <div class="gender">
            <label>
                <input type="radio" required name="gender" value="male" checked>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                    <path d="M780-780v200h-40v-130.69L534.85-507.31q19.77 28.77 32.46 60.77Q580-414.54 580-380q0 83.64-58.16 141.82Q463.68-180 380.07-180q-83.61 0-141.84-58.16Q180-296.32 180-379.93q0-83.61 58.18-141.84Q296.36-580 380-580q34.54 0 66.15 12.58 31.62 12.57 60.16 32.57L711.46-740H580v-40h200ZM379.88-540q-66.34 0-113.11 46.89Q220-446.21 220-379.88q0 66.34 46.89 113.11Q313.79-220 380.12-220q66.34 0 113.11-46.89Q540-313.79 540-380.12q0-66.34-46.89-113.11Q446.21-540 379.88-540Z" />
                </svg>
                <span>Male</span>
                <div class="bg"></div>
            </label>
            <label>
                <input type="radio" required name="gender" value="female">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                    <path d="M460-140v-80h-80v-40h80v-120.92q-76.69-8.62-128.35-65.5Q280-503.31 280-580.46q0-83.3 58.35-141.42Q396.69-780 480-780t141.65 58.12Q680-663.76 680-580.46q0 77.15-51.65 134.04-51.66 56.88-128.35 65.5V-260h80v40h-80v80h-40Zm20.12-280q66.34 0 113.11-46.89Q640-513.79 640-580.12q0-66.34-46.89-113.11Q546.21-740 479.88-740q-66.34 0-113.11 46.89Q320-646.21 320-579.88q0 66.34 46.89 113.11Q413.79-420 480.12-420Z" />
                </svg>
                <span>Female</span>
                <div class="bg"></div>
            </label>
        </div>
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