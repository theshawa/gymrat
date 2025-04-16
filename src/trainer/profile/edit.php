<?php
// trainer/profile/edit.php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

// Initialize trainer data with default values
$trainer = array_merge([
    'name' => '',
    'username' => '',
    'bio' => '',
    'avatar' => null
], $_SESSION['auth'] ?? []);

$pageConfig = [
    "title" => "Edit Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css", "./edit.css"],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<main>
    <!-- <div class="avatar-input-wrapper">
        <img src="<?= !empty($trainer['avatar']) ? $trainer['avatar'] : './avatar.webp' ?>" alt="Profile Picture"
            class="avatar">
        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
        <button type="button" class="clear-avatar-button">Change Photo</button>
    </div> -->

    <form action="profile_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">

        <div class="form-field">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($trainer['fname']) ?>" required>
        </div>

        <div class="form-field">
            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($trainer['lname']) ?>" required>
        </div>

        <!-- <div class="form-field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($trainer['username']) ?>"
                required>
        </div> -->

        <div class="form-field">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4"><?= htmlspecialchars($trainer['bio']) ?></textarea>
        </div>

        <button type="submit" class="external-link btn">SAVE</button>
    </form>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>