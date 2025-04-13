<?php

$pageConfig = [
    "title" => "Client Status",
    "styles" => [
        "./profile.css" // The CSS file we'll create
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
        "title" => "CLIENT STATUS"
    ],
    "need_auth" => true
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get customer data
require_once "../data.php"; // Using the mock data file

// Find customer by ID
$customer = null;
foreach ($data as $item) {
    if ($item['id'] == $customerId) {
        $customer = $item;
        break;
    }
}

// If customer not found, redirect back to customers list
if (!$customer) {
    header("Location: ../");
    exit;
}

// Mock data for customer details
$customerDetails = [
    'username' => '@' . strtolower($customer['fname']) . '_' . strtolower($customer['lname']),
    'goal' => 'My Goal is to burn my fat in my tummy and gain muscles in forearms within 9 months.',
    'rating' => 4.7,
    'profile_image' => '/uploads/default-images/default-avatar.png'
];
?>

<main class="profile-view">
    <!-- Customer Profile Section -->
    <div class="profile-section">
        <div class="profile-info">
            <img src="<?= $customerDetails['profile_image'] ?>" alt="Profile" class="profile-avatar">
            <h1 class="profile-name"><?= $customer['fname'] . ' ' . $customer['lname'] ?></h1>
            <p class="profile-username"><?= $customerDetails['username'] ?></p>
            <p class="profile-goal"><?= $customerDetails['goal'] ?></p>
        </div>

        <div class="rating-box">
            <div class="rating-score"><?= $customerDetails['rating'] ?></div>
            <div class="rating-stars">
                <span class="star filled">★</span>
                <span class="star filled">★</span>
                <span class="star filled">★</span>
                <span class="star filled">★</span>
                <span class="star">★</span>
            </div>
        </div>
    </div>

    <!-- Action Tiles Section -->
    <div class="action-tiles">
        <div class="action-row">
            <a href="./workout?id=<?= $customerId ?>" class="action-tile purple">
                <span>Current<br>Workout</span>
                <div class="action-arrow">
                    <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                                fill="#FAFAFA" />
                        </svg>
                    </span>
                </div>
            </a>
            <a href="./meal-plan?id=<?= $customerId ?>" class="action-tile purple">
                <span>Current<br>Meal Plan</span>
                <div class="action-arrow">
                    <span><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.05033 10.9497C0.757435 10.6569 0.757435 10.182 1.05033 9.88909L8.57247 2.36694L2.8328 2.50721C2.41866 2.51533 2.07636 2.1862 2.06824 1.77206C2.06012 1.35793 2.38926 1.01562 2.80339 1.0075L10.4048 0.830727C10.6088 0.826728 10.8056 0.905994 10.9498 1.05025C11.0941 1.19451 11.1733 1.39131 11.1693 1.59529L10.9926 9.19668C10.9845 9.61082 10.6421 9.93996 10.228 9.93184C9.81388 9.92372 9.48474 9.58141 9.49286 9.16728L9.63313 3.4276L2.11099 10.9497C1.81809 11.2426 1.34322 11.2426 1.05033 10.9497Z"
                                fill="#FAFAFA" />
                        </svg>
                    </span>
                </div>
            </a>
        </div>

        <a href="./add-log?id=<?= $customerId ?>" class="action-tile purple full-width">
            <span>Add Log Record</span>
        </a>

        <a href="./report?id=<?= $customerId ?>" class="action-tile red full-width">
            <span>Report Client</span>
        </a>
    </div>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>