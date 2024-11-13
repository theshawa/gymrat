<?php
$pageConfig = [
    "title" => "Register",
    "styles" => ["/rat/styles/auth.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../includes/header.php";

?>

<main>
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
            <input required class="input" type="tel" placeholder="Contact no." name="phone">
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
</script>

<?php require_once "../includes/footer.php" ?>