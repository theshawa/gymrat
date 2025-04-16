<?php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

$pageConfig = [
    "title" => "My Customers",
    "styles" => [
        "./customers.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../"
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
require_once "../../db/models/Customer.php";
require_once "../../db/Database.php";
require_once "../../alerts/functions.php";

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get customers from database
$customers = [];
try {
    // Get database connection
    $conn = Database::get_conn();

    // Build query based on search
    $sql = "SELECT * FROM customers";

    // Add search functionality
    if (!empty($search)) {
        $sql .= " WHERE fname LIKE :search OR lname LIKE :search OR email LIKE :search";
        $stmt = $conn->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    redirect_with_error_alert("Error fetching customers: " . $e->getMessage(), "../");
}
?>

<main>
    <!-- Search functionality -->
    <input type="search" name="search" id="searchInput" class="input" placeholder="Search customers..."
        value="<?= htmlspecialchars($search) ?>">

    <div class="customers-list">
        <?php if (count($customers) === 0): ?>
            <div class="no-results">
                <p style="text-align: center; padding: 20px; color: #a1a1aa;">No customers found</p>
            </div>
        <?php else: ?>
            <?php foreach ($customers as $customer): ?>
                <a class="customer" href="./profile?id=<?= $customer['id'] ?>">
                    <?php
                    // Correctly handle the avatar path
                    $avatarPath = '/uploads/default-images/default-avatar.png'; // Default

                    if (!empty($customer['avatar'])) {
                        // Check if avatar already starts with "/uploads/"
                        if (strpos($customer['avatar'], '/uploads/') === 0) {
                            $avatarPath = $customer['avatar'];
                        }
                        // Check if it starts with "uploads/"
                        else if (strpos($customer['avatar'], 'uploads/') === 0) {
                            $avatarPath = '/' . $customer['avatar'];
                        }
                        // Otherwise, assume it's in "customer-avatars/"
                        else {
                            $avatarPath = '/uploads/' . $customer['avatar'];
                        }
                    }
                    ?>
                    <img src="<?= $avatarPath ?>" alt="Avatar Image" class="avatar">
                    <h4><?= htmlspecialchars($customer['fname'] . " " . $customer['lname']) ?></h4>
                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 9 9" fill="none">
                        <path
                            d="M8.50002 1.46445C8.50002 1.18831 8.27617 0.964455 8.00002 0.964455L3.50002 0.964455C3.22388 0.964455 3.00002 1.18831 3.00002 1.46445C3.00002 1.7406 3.22388 1.96445 3.50002 1.96445L7.50002 1.96445L7.50002 5.96445C7.50002 6.2406 7.72388 6.46445 8.00002 6.46445C8.27617 6.46445 8.50002 6.2406 8.50002 5.96445L8.50002 1.46445ZM1.28251 8.88908L8.35358 1.81801L7.64647 1.1109L0.575402 8.18197L1.28251 8.88908Z"
                            fill="#52525B" />
                    </svg>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<!-- Script for search functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let debounceTimer;

        // Function to handle search
        function handleSearch() {
            const searchTerm = searchInput.value.trim();

            // Clear any pending debounce
            clearTimeout(debounceTimer);

            // Set a timeout to perform the search
            debounceTimer = setTimeout(() => {
                // If search is empty, redirect to the base URL
                if (searchTerm === '') {
                    window.location.href = window.location.pathname;
                    return;
                }

                // Otherwise create the search URL and navigate
                const searchParams = new URLSearchParams();
                searchParams.set('search', searchTerm);
                window.location.href = window.location.pathname + '?' + searchParams.toString();
            }, 500);
        }

        // Listen for keyup events (typing)
        searchInput.addEventListener('keyup', handleSearch);

        // Listen for input events (including when user clears with X)
        searchInput.addEventListener('input', function() {
            // If the field is empty, reload immediately
            if (this.value === '') {
                clearTimeout(debounceTimer);
                window.location.href = window.location.pathname;
            }
        });
    });
</script>

<style>
    /* Ensure search input maintains original width */
    input[type="search"] {
        width: 100%;
        padding: 15px 20px;
        border-radius: 9999px;
        background-color: var(--color-zinc-900);
        color: white;
        border: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2371717a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 15px) center;
        padding-right: 40px;
    }

    /* Fix the width on small screens */
    @media (max-width: 768px) {
        input[type="search"] {
            width: 100%;
        }
    }
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>