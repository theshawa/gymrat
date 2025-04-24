<?php
require_once "../../auth-guards.php";
if (auth_not_required_guard("rat", "/rat")) exit;

$pageConfig = [
    "title" => "Register",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../includes/header.php";

?>

<main class="auth">
    <div class="content">
        <img src="../assets/logo-gray.svg" alt="Logo">
        <h1 class="alt">Create Account</h1>
        <form class="form" action="register_process.php" method="post" enctype="multipart/form-data">
            <label title="Add avatar" class="avatar">
                <input type="file" name="avatar" id="avatarInput" accept="image/*">
                <svg class="icon" xmlns=" http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                <img class="preview" src="" alt="Avatar preview">
            </label>
            <input required class="input" type="text" placeholder="First name" name="fname">
            <input required class="input" type="text" placeholder="Last name" name="lname">
            <input required class="input" type="tel" pattern="(\+94|0)[0-9]{9}" placeholder="+94XXXXXXXXX" name="phone">
            <input required class="input" type="email" placeholder="Email" name="email">
            <label for="password" class="password-field">
                <input required type="password" name="password" placeholder="Password" minlength="6">
            </label>
            <label for="cpassword" class="password-field">
                <input required type="password" name="cpassword" placeholder="Confirm Password">
            </label>
            <button class="btn">Register</button>
        </form>
        <a href="../login" class="dimmed-link">
            Already registered?
        </a>
    </div>
</main>

<script>
    // handle avatar preview(Theshawa)
    const avatarInput = document.getElementById("avatarInput");
    const avatarPreview = document.querySelector(".avatar .preview");
    const avatarIcon = document.querySelector(".avatar .icon");

    avatarInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) {
            avatarPreview.src = "";
            avatarIcon.style.display = "block";
            avatarPreview.style.display = "none";
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.src = e.target.result;
            avatarIcon.style.display = "none";
            avatarPreview.style.display = "block";
        }
        reader.readAsDataURL(file);
    });

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

<?php require_once "../includes/footer.php" ?>