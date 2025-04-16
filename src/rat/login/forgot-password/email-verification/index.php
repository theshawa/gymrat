<?php
require_once "../../../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

if (!isset($_SESSION['customer_password_reset'])) {
    die("No password reset data found. Please request a password reset first.");
}

$pageConfig = [
    "title" => "Email Verification | Forgot Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../../includes/header.php";
?>

<main class="auth">
    <div class="content">
        <img src="../../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Verify Email</h1>
        <form class="form" action="email_verification_process.php" method="post">
            <input required class="input" type="text" placeholder="Enter code" name="code">
            <button class="btn">Verify</button>
        </form>
        <a href="resend_process.php" class="dimmed-link">
            Resend code
        </a>
    </div>
</main>

<?php require_once "../../../includes/footer.php" ?>