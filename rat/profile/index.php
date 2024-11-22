<?php
$pageConfig = [
    "title" => "My Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css"],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";


require_once "../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get user due to error: " . $e->getMessage(), "./");
}

$avatar = $user->avatar ? "/uploads/" . $user->avatar : "/uploads/default-images/default-avatar.png";

?>

<main>
    <img src="<?= $avatar ?>" alt="" class="avatar">
    <h1><?= $user->fname ?> <?= $user->lname ?></h1>
    <div class="lines">
        <div class="line">
            <span class="title">Email</span>
            <a href="mailto:<?= $user->email ?>" class="content"><?= $user->email ?></a>
        </div>
        <div class="line">
            <span class="title">Phone</span>
            <a href="tel:<?= $user->phone ?>" class="content"><?= $user->phone ?></a>
        </div>
        <div class="line">
            <span class="title">Joined at</span>
            <p class="content"><?= $user->created_at->format("M d, Y, h:i A") ?></p>
        </div>
        <?php if ($user->updated_at->format("M d, Y, h:i A") !== $user->created_at->format("M d, Y, h:i A")): ?>
            <div class="line">
                <span class="title">Last updated at</span>
                <p class="content"><?= $user->updated_at->format("M d, Y, h:i A") ?></p>
            </div>
        <?php endif; ?>
    </div>
    <a href="./update" class="btn">Update Profile</a>
    <a href="../logout.php" class="btn secondary">Logout</a>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>