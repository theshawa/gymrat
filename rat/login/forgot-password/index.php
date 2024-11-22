<?php
$pageConfig = [
    "title" => "Forgot Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "need_auth" => false
];

require_once "../../includes/header.php";

?>

<main class="auth">
    <div class="content">
        <img src="../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Forgot Password?</h1>
        <form class="form" action="forgot_password_process.php" method="post">
            <input required class="input" type="email" placeholder="Enter your email" name="email">
            <button class="btn" name="action" value="send">Next</button>
        </form>
        <a href="/rat/login" class="dimmed-link">
            Go to login
        </a>
    </div>
</main>

<?php require_once "../../includes/footer.php" ?>