<?php
$pageConfig = [
    "title" => "My Customers",
    "styles" => [
        "./customers.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../"
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "./data.php";

?>

<main>
    <input type="search" name="" id="" class="input" placeholder="Search customers...">
    <div class="customers-list">
        <?php foreach ($data as $customer) : ?>
            <a class="customer" href="./profile?id=<?= $customer['id'] ?>">
                <img src="/uploads/default-images/default-avatar.png" alt="Avatar Image" class="avatar">
                <h4><?= $customer['fname'] . " " . $customer['lname'] ?></h4>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 9 9" fill="none">
                    <path d="M8.50002 1.46445C8.50002 1.18831 8.27617 0.964455 8.00002 0.964455L3.50002 0.964455C3.22388 0.964455 3.00002 1.18831 3.00002 1.46445C3.00002 1.7406 3.22388 1.96445 3.50002 1.96445L7.50002 1.96445L7.50002 5.96445C7.50002 6.2406 7.72388 6.46445 8.00002 6.46445C8.27617 6.46445 8.50002 6.2406 8.50002 5.96445L8.50002 1.46445ZM1.28251 8.88908L8.35358 1.81801L7.64647 1.1109L0.575402 8.18197L1.28251 8.88908Z" fill="#52525B" />
                </svg>
            </a>
        <?php endforeach; ?>

    </div>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>