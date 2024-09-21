<?php
$pageConfig = [
    "title" => "Login",
    "styles" => ["./styles.css"],
    "scripts" => ["/scripts/forms.js"]
];

include_once "../includes/header.php";
?>

<main>
    <div class="content">
        <img src="../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Welcome Back!</h2>
            <form action="login_process.php" method="post">
                <input required class="input" type="email" placeholder="Email" name="email">
                <label for="password" class="password-field">
                    <input required type="password" name="password" placeholder="Password">
                </label>
                <button class="btn">Log in</button>
            </form>
            <a href="/forgot-password" class="forgot-password">
                Forgot your password?
            </a>
    </div>
</main>

<?php include_once "../includes/footer.php" ?>