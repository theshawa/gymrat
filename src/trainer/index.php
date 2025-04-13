<?php
$pageConfig = [
    "title" => "Home",
    "styles" => [
        "./home.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Welcome!",
    ],
    "need_auth" => true
];

require_once "./includes/header.php";

if (isset($_SESSION['auth'])) {
    $fname = $_SESSION['auth']['fname'];
    $pageConfig['titlebar']['title'] = "Hi, $fname!";
}

require_once "./includes/titlebar.php";

?>


<main>
    <div class="grid">
        <a href="/trainer/customers" class="tile">
            <span>
                My<br />
                Customers
            </span>
        </a>
        <a href="/trainer/ratings" class="tile">
            <span>
                My<br />
                Ratings
            </span>
        </a>
        <a href="/trainer/post-announcement" class="tile full-width">
            <span>
                Post<br />
                Announcement
            </span>
        </a>
        <a href="/trainer/complaint" class="tile gray full-width">
            <span>
                Make Complaint
            </span>
        </a>
    </div>
</main>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>