<?php

$pageConfig = [
    "title" => "Start Workout",
    "styles" => ["./start_workout.css"],
    "scripts" => ["https://unpkg.com/html5-qrcode", "./start_workout.js"],
    "titlebar" => [
        "title" => "Start Workout",
        "back_url" => "./"
    ],
    "navbar_active" => 1,
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>
<main>
    <div class="info">
        <h2>Scan QR code to continue</h2>
        <p class="paragraph small">Please ask your gym manager for the QR code to proceed with your workout.</p>
    </div>
    <div id="reader"></div>
    <div class="camera-selection">
        <label for="">Select Camera</label>
        <select class="input"></select>
    </div>
    <p class="error-message"></p>
    <button class="btn outlined try-again">Try Again</button>
</main>

<?php
require_once "../../includes/navbar.php";
require_once "../../includes/footer.php";
?>