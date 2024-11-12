<?php
$pageConfig = [
    "title" => "Forgot Password",
    "styles" => ["../login.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../includes/header.php";

$isOTPVerification = $_SESSION['forgot_password_otp'] ?? null;
$error = $_SESSION['error'] ?? null;
if ($error) {
    unset($_SESSION['error']);
}
?>

<main>
    <div class="content">
        <img src="../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Forgot Password?</h1>
        <?php if ($isOTPVerification): ?>
            <form class="form" action="forgot_password_process.php" method="post">
                <p class="paragraph">Check your mail inbox for an OTP code. If not found please check the spam.</p>
                <input required class="input" type="text" placeholder="Enter OTP" name="otp">
                <button class="btn" name="action" value="verify">Verify</button>
            </form>
            <form class="form" action="forgot_password_process.php" method="post">
                <button href="/rat/login" class="dimmed-link" name="action" value="resend">
                    Resend
                </button>
            </form>
        <?php else: ?>
            <form class="form" action="forgot_password_process.php" method="post">
                <input required class="input" type="email" placeholder="Enter your email" name="email">
                <button class="btn" name="action" value="send">Next</button>
            </form>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error-message"><?= $error ?>!</p>
        <?php endif; ?>
        <a href="/rat/login" class="dimmed-link">
            Go to login
        </a>
    </div>
</main>

<?php require_once "../../includes/footer.php" ?>