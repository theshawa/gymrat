<?php

$pageConfig = [
    "title" => "Verify Email",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../includes/header.php";

require_once "../../../alerts/functions.php";

if (!isset($_SESSION['customer_registration'])) {
    redirect_with_error_alert("Invalid request", "/rat/register");
}

?>

<main class="auth">
    <div class="content">
        <img src="../../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Verify your email!</h1>
        <form class="form" action="email_verification_process.php" method="post">
            <input required class="input" type="text" placeholder="Code" name="code">
            <button class="btn">Verify</button>
        </form>
        <a href="resend.php" class="dimmed-link">
            Resend code
        </a>
    </div>
</main>

<?php require_once "../../includes/footer.php" ?>