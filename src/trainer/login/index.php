<?php
$pageConfig = [
    "title" => "Login",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "need_auth" => false
];

require_once "../includes/header.php";

?>

<main class="auth">
    <div class="content">
        <img src="/rat/assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Welcome!</h1>
        <form class="form" action="login_process.php" method="post">
            <input required class="input" type="text" placeholder="Username" name="username">
            <label for="password" class="password-field">
                <input required type="password" name="password" placeholder="Password">
            </label>
            <button class="btn">Log in</button>
        </form>
    </div>
</main>

<?php require_once "../includes/footer.php" ?>