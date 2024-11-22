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
        <img src="../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Welcome Back!</h1>
        <form class="form" action="login_process.php" method="post">
            <input required class="input" type="email" placeholder="Email" name="email">
            <label for="password" class="password-field">
                <input required type="password" name="password" placeholder="Password">
            </label>
            <button class="btn">Log in</button>
        </form>
        <a href="forgot-password" class="dimmed-link">
            Forgot your password?
        </a><a href="../register" class="dimmed-link">
            Not registered yet?
        </a>
    </div>
</main>

<?php require_once "../includes/footer.php" ?>