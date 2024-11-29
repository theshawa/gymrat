<?php
$pageConfig = [
    "title" => "My Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css"],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

$user = $_SESSION['auth'];

$avatar = "./avatar.webp";

?>

<main>
    <img src="<?= $avatar ?>" alt="" class="avatar">
    <h1>John Cena</h1>
    <div class="lines">
        <div class="line">
            <span class="title">Email</span>
            <a href="mailto:<?= $user['email'] ?>" class="content"><?= $user['email'] ?></a>
        </div>
        <div class="line">
            <span class="title">Phone</span>
            <a href="tel: +94766743755" class="content">+94766743755</a>
        </div>
        <div class="line">
            <span class="title">Joined at</span>
            <p class="content"><?= (new DateTime("2/2/2024"))->format("M d, Y") ?></p>
        </div>
        <div class="line">
            <span class="title">Last updated at</span>
            <p class="content"><?= (new DateTime("2/2/2024"))->format("M d, Y") ?></p>
        </div>
        <a href="../logout.php" class="btn secondary">Logout</a>
    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>