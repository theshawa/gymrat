<?php
// trainer/profile/edit.php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../db/models/Trainer.php";

$trainer = new Trainer();
$trainer->fill([
    "id" => $_SESSION['auth']['id']
]);
try {
    $trainer->get_by_id();
} catch (PDOException $e) {
    die("Failed to get trainer data due to error: " . $e->getMessage());
}

$avatar = $trainer->avatar ? "/uploads/" . $trainer->avatar : "/uploads/default-images/default-avatar.png";

$pageConfig = [
    "title" => "Edit Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css", "./edit.css"],
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <!-- Avatar upload section with preview -->
    <div class="avatar-container">
        <img src="<?= $avatar ?>" alt="Profile Picture" class="profile-avatar" id="avatar-preview">
        <div class="avatar-upload-controls">
            <label for="avatar" class="avatar-upload-button">
                Change Photo
                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
            </label>
            <?php if ($trainer->avatar): ?>
                <button type="button" id="clear-avatar-button" class="clear-avatar-button">
                    Remove Photo
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Profile editing form -->
    <form action="profile_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">
        <input type="hidden" name="updated_avatar" id="updated_avatar" value="<?= $trainer->avatar ?>">

        <div class="form-field">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($trainer->fname) ?>" required>
        </div>

        <div class="form-field">
            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($trainer->lname) ?>" required>
        </div>

        <div class="form-field">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4"><?= htmlspecialchars($trainer->bio) ?></textarea>
        </div>

        <div class="form-field">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="(\+94|0)[0-9]{9}"
                value="<?= htmlspecialchars($trainer->phone) ?>" placeholder="+94XXXXXXXXX" required>
        </div>

        <button type="submit" class="btn">SAVE</button>
    </form>

    <!-- JavaScript for avatar preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatar-preview');
            const updatedAvatarInput = document.getElementById('updated_avatar');
            const clearAvatarButton = document.getElementById('clear-avatar-button');

            // Handle file selection
            avatarInput.addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        avatarPreview.src = e.target.result;
                    };

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Handle avatar removal if clear button exists
            if (clearAvatarButton) {
                clearAvatarButton.addEventListener('click', function () {
                    // Set default avatar
                    avatarPreview.src = "/uploads/default-images/default-avatar.png";
                    // Clear the file input
                    avatarInput.value = '';
                    // Set updated_avatar to empty to indicate removal
                    updatedAvatarInput.value = '';
                });
            }
        });
    </script>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>