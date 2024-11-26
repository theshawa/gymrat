<?php
$pageConfig = [
    "title" => "Email Verification | Forgot Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "need_auth" => false
];

require_once "../../../includes/header.php";

require_once "../../../../alerts/functions.php";

if (!isset($_SESSION['customer_password_reset'])) {
    redirect_with_error_alert("Invalid request", "../");
}

?>

<main class="auth">
    <div class="content">
        <img src="../../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Verify Email</h1>
        <form class="form" action="email_verification_process.php" method="post">
            <input required class="input" type="text" placeholder="Enter code" name="code">
            <button class="btn">Verify</button>
        </form>
        <a href="resend.php" class="dimmed-link">
            Resend code
        </a>
    </div>
</main>

<?php require_once "../../../includes/footer.php" ?>