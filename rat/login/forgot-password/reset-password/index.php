<?php

$pageConfig = [
    "title" => "Reset Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../../includes/header.php";

if (!isset($_SESSION['forgot_password_otp'])) {
    die("Your attempt to reset password is invalid! Please try again.");
}
unset($_SESSION['forgot_password_otp']);
?>

<main class="auth">
    <div class="content">
        <img src="../../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Reset Password</h1>
        <p class="paragraph">Please note that you'll get only one chance to reset the password for the entered OTP. Check befor submit!</p>
        <br>
        <form class="form" action="reset_password_process.php" method="post">
            <label for="password" class="password-field">
                <input required class="input" type="password" placeholder="New Password" name="password">
            </label>
            <label for="repeat_password" class="password-field">
                <input required type="password" name="repeat_password" placeholder="Repeat password">
            </label>
            <button class="btn">Reset</button>
        </form>
        <a href="/rat/login" class="dimmed-link">
            Cancel
        </a>
    </div>
</main>

<?php require_once "../../../includes/footer.php" ?>