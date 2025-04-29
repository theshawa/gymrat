<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;


require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    die("Failed to get user due to error: " . $e->getMessage());
}

$avatar = $user->avatar ? "/uploads/" . $user->avatar : null;

$pageConfig = [
    "title" => "Update Profile",
    "styles" => ["/rat/styles/auth.css", "../profile.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 3
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="auth" style="padding-top: 0;">
    <div class="content">
        <form class="form" action="update_basic_info_process.php" method="post" enctype="multipart/form-data">
            <div class="avatar-input-wrapper">
                <label title="Add avatar" class="avatar">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*">
                    <svg class="icon" xmlns=" http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <img class="preview" src="" alt="Avatar preview">
                    <input type="hidden" name="updated_avatar" value="<?= $user->avatar ?>">
                </label>
                <button class="clear-avatar-button">Clear Avatar</button>
            </div>
            <div class="form-field">
                <label for="fname">First Name</label>
                <input required class="input" type="text" placeholder="<?= $user->fname ?>" name="fname" value="<?= $user->fname ?>">
            </div>
            <div class="form-field">
                <label for="fname">Last name</label>
                <input required class="input" type="text" placeholder="<?= $user->lname ?>" name="lname" value="<?= $user->lname ?>">
            </div>
            <div class="form-field">
                <label for="fname">Contact no.</label>
                <input required class="input" type="tel" pattern="(\+94|0)[0-9]{9}" placeholder="<?= $user->phone ?>" name="phone" value="<?= $user->phone ?>">
            </div>
            <button class="btn">Save</button>
        </form>
        <form action="update_password_process.php" method="post" class="form" style="margin-top: 40px;">
            <h4>Update Password</h4>
            <label for="current_password" class="password-field">
                <input type="password" name="current_password" placeholder="Current Password" required minlength="6">
            </label>
            <label for="password" class="password-field">
                <input type="password" name="password" placeholder="New Password" required minlength="6">
            </label>
            <label for="cpassword" class="password-field">
                <input type="password" name="cpassword" placeholder="Confirm New Password" required minlength="6">
            </label>
            <button class="btn">Update Password</button>
        </form>
        <!-- <a href="../update-initial-data" class="btn outlined" style="margin-top: 40px;width: 100%;">Update initial data</a> -->
    </div>
</main>

<script>
    let avatar = <?= json_encode($avatar) ?>;

    // handle avatar preview(Theshawa)
    const avatarInput = document.getElementById("avatarInput");
    const clearAvatarButton = document.querySelector(".clear-avatar-button");
    const avatarPreview = document.querySelector(".avatar .preview");
    const avatarIcon = document.querySelector(".avatar .icon");
    const avatarInputHidden = document.querySelector("input[name=updated_avatar]");


    if (avatar) {
        avatarPreview.src = avatar;
        avatarIcon.style.display = "none";
        avatarPreview.style.display = "block";
        clearAvatarButton.style.display = "block";
    } else {
        avatarIcon.style.display = "block";
        avatarPreview.style.display = "none";
        clearAvatarButton.style.display = "none";
    }

    avatarInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) {
            avatarPreview.src = "";
            avatarIcon.style.display = "block";
            avatarPreview.style.display = "none";
            clearAvatarButton.style.display = "none";
            avatarInputHidden.value = "";
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.src = e.target.result;
            avatarIcon.style.display = "none";
            avatarPreview.style.display = "block";
            clearAvatarButton.style.display = "block";
            avatarInputHidden.value = file.name;
        }
        reader.readAsDataURL(file);
    });

    clearAvatarButton.addEventListener("click", (e) => {
        e.preventDefault();
        avatarInput.value = null;
        avatarPreview.src = "";
        avatarIcon.style.display = "block";
        avatarPreview.style.display = "none";
        clearAvatarButton.style.display = "none";
        avatarInputHidden.value = "";
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


    document.querySelector('form[action="update_password_process.php"]').addEventListener("submit", (e) => {
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
            alert("New password is weak. " + tips.join(" "));
            return;
        }
        if (level === "Medium") {
            const confirm = window.confirm("New password is not strong. Do you want to continue?\n" + tips.join("\n"));
            if (!confirm) {
                return;
            }
        }

        e.target.submit();
    })
</script>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>