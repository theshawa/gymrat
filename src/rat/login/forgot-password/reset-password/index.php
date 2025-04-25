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

<script>
    const checkPasswordStrength = (password) => {
        let strength = 0;
        let tips = [];

        if (password.length < 8) {
            tips.push("Password should be at least 8 characters long.");
        } else {
            strength++;
        }

        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 1;
        } else {
            tips.push("Use both lowercase and uppercase letters.");
        }

        if (/\d/.test(password)) {
            strength += 1;
        } else {
            tips.push("Include at least one number.");
        }


        if (/[^a-zA-Z\d]/.test(password)) {
            strength += 1;
        } else {
            tips.push("Include at least one special character.");
        }

        let level;
        if (strength < 2) {
            level = "Weak";
        } else if (strength === 2) {
            level = "Medium";
        } else if (strength === 3) {
            level = "Strong";
        } else {
            level = "Very Strong";
        }

        return {
            level: level,
            tips: tips
        };
    }


    document.forms[0].addEventListener("submit", (e) => {
        e.preventDefault();
        console.log(e);
        const pw = document.querySelector("input[name='password']").value;
        const cpw = document.querySelector("input[name='cpassword']").value;
        if (pw !== cpw) {
            alert("Passwords do not match");
            return;
        }
        // check password strength
        const {
            level,
            tips
        } = checkPasswordStrength(pw);
        if (level === "Weak") {
            alert("Password is weak. " + tips.join(" "));
            return;
        }
        if (level === "Medium") {
            const confirm = window.confirm("Password is not strong. Do you want to continue?\n" + tips.join("\n"));
            if (!confirm) {
                return;
            }
        }

        e.target.submit();
    })
</script>

<?php require_once "../../../includes/footer.php" ?>