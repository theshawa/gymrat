<?php
$pageConfig = [
    "title" => "Reset Password",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "need_auth" => false
];

require_once "../../../includes/header.php";

require_once "../../../../alerts/functions.php";

if (!isset($_SESSION['customer_password_reset'])) {
    redirect_with_error_alert("Invalid request", "../");
}

if (!isset($_SESSION['customer_password_reset']['verified'])) {
    redirect_with_error_alert("Please verify email", "../");
}

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