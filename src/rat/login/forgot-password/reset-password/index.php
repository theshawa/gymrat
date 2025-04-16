<?php
require_once "../../../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

if (!isset($_SESSION['customer_password_reset'])) {
    die("No password reset data found. Please request a password reset first.");
}

if (!isset($_SESSION['customer_password_reset']['verified'])) {
    die("Email not verified. Please verify your email first.");
}

$pageConfig = [
    "title" => "Reset Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../../includes/header.php";
?>

<main class="auth">
    <div class="content">
        <img src="../../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Reset Password</h1>
        <form class="form" action="reset_password_process.php" method="post">
            <label for="password" class="password-field">
                <input required type="password" name="password" placeholder="Password">
            </label>
            <label for="password" class="password-field">
                <input required type="password" name="cpassword" placeholder="Retype Password">
            </label>
            <button class="btn">Submit</button>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php" ?>