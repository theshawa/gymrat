<?php

$pageConfig = [
    "title" => "Login",
    "styles" => ["./login.css"],
];

include_once "../includes/header.php";
?>

<main>
    <form action="login_process.php" method="post" class="form">
        <h1 style="margin-bottom: 12px;">Staff Login</h1>
        <input type="email" required placeholder="Email" name="email" class="input">
        <input type="password" required placeholder="Password" name="password" class="input">
        <button class="btn">Login</button>
    </form>
</main>

<?php include_once "../includes/footer.php"; ?>