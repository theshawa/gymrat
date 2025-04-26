<?php
require_once "../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

// Get active customer count
require_once "../db/models/Customer.php";
$customerModel = new Customer();
$activeCustomers = [];
try {
    $activeCustomers = $customerModel->get_all_by_trainer($_SESSION['auth']['id']);
} catch (Exception $e) {
    // Handle error silently
}
$activeCustomerCount = count($activeCustomers);

// Get trainer ratings
require_once "../db/models/TrainerRating.php";
$ratingModel = new TrainerRating();
$ratingData = ['avg_rating' => 0, 'review_count' => 0];
try {
    $ratingData = $ratingModel->get_rating_of_trainer($_SESSION['auth']['id']);
} catch (Exception $e) {
    // Handle error silently
}

// Get pending workout or meal plan requests
require_once "../db/models/WorkoutRequest.php";
require_once "../db/models/MealPlanRequest.php";
$workoutRequestModel = new WorkoutRequest();
$mealPlanRequestModel = new MealPlanRequest();
$pendingWorkoutRequests = 0;
$pendingMealPlanRequests = 0;
try {
    $pendingWorkoutRequests = $workoutRequestModel->has_unreviewed_requests() ? 1 : 0;
    $pendingMealPlanRequests = $mealPlanRequestModel->has_unreviewed_requests() ? 1 : 0;
} catch (Exception $e) {
    // Handle error silently
}

// Get the 5 most recent clients or clients needing attention
$recentClients = [];
if (!empty($activeCustomers)) {
    // Sort by most recent updates or some criteria
    // Limit to 5 clients
    $recentClients = array_slice($activeCustomers, 0, 5);
}

$pageConfig = [
    "title" => "Dashboard",
    "styles" => [
        "./trainer-dashboard.css"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "title" => "Dashboard",
    ]
];

require_once "./includes/header.php";
require_once "./includes/titlebar.php";

// Get trainer's first name for personalized greeting
$trainerName = $_SESSION['auth']['fname'] ?? 'Trainer';

// Get time-based greeting
$hour = date('H');
$timeGreeting = "Good Morning";
if ($hour >= 12 && $hour < 17) {
    $timeGreeting = "Good Afternoon";
} elseif ($hour >= 17) {
    $timeGreeting = "Good Evening";
}
?>

<main class="dashboard-container">
    <!-- Dashboard Header Section -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1><?= $timeGreeting ?>, <?= htmlspecialchars($trainerName) ?>!</h1>
            <p><?= date('l, j F Y') ?></p>
        </div>
        <div class="quick-actions">
            <a href="/trainer/notifications" class="quick-action-button" title="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
            </a>
            <a href="/trainer/profile" class="quick-action-button" title="Your Profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-label">Active Clients</div>
            <div class="stat-value"><?= $activeCustomerCount ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z">
                    </path>
                </svg>
            </div>
            <div class="stat-label">Rating</div>
            <div class="stat-value"><?= number_format($ratingData['avg_rating'], 1) ?><span
                    style="font-size: 14px;">/5</span></div>
            <div class="stat-trend positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                Based on <?= $ratingData['review_count'] ?> reviews
            </div>
        </div>
    </div>

    <!-- Main Features Section -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
        </div>

        <div class="cards-grid">
            <a href="/trainer/customers" class="feature-card">
                <div class="feature-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="feature-card-content">
                    <div class="feature-card-title">Manage Clients</div>
                    <p class="feature-card-description">View and manage all your clients, assess progress, and update
                        profiles</p>
                </div>
                <svg class="feature-card-decoration" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                    viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </a>

            <a href="/trainer/post-announcement" class="feature-card">
                <div class="feature-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                    </svg>
                </div>
                <div class="feature-card-content">
                    <div class="feature-card-title">Announcements</div>
                    <p class="feature-card-description">Send important updates to all your clients at once</p>
                </div>
                <svg class="feature-card-decoration" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                    viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                </svg>
            </a>

            <a href="/trainer/ratings" class="feature-card">
                <div class="feature-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z">
                        </path>
                    </svg>
                </div>
                <div class="feature-card-content">
                    <div class="feature-card-title">My Ratings</div>
                    <p class="feature-card-description">View client feedback and track your performance metrics</p>
                </div>
                <svg class="feature-card-decoration" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                    viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path
                        d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z">
                    </path>
                </svg>
            </a>

            <a href="/trainer/complaint" class="feature-card">
                <div class="feature-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                </div>
                <div class="feature-card-content">
                    <div class="feature-card-title">Support & Feedback</div>
                    <p class="feature-card-description">Report issues or share improvement suggestions</p>
                </div>
                <svg class="feature-card-decoration" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                    viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Client Attention Section -->
    <?php if (!empty($recentClients)): ?>
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Clients Requiring Attention</h2>
                <a href="/trainer/customers" class="view-all">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="client-list">
                <div class="client-list-header">
                    <div class="client-list-title">Recent Activity</div>
                </div>

                <?php foreach ($recentClients as $client):
                    // Get default avatar path
                    $avatarPath = '/
                    
                    
                    uploads/default-images/default-avatar.png';
                    if (!empty($client->avatar)) {
                        if (strpos($client->avatar, '/uploads/') === 0) {
                            $avatarPath = $client->avatar;
                        } else if (strpos($client->avatar, 'uploads/') === 0) {
                            $avatarPath = '/' . $client->avatar;
                        } else {
                            $avatarPath = '/uploads/' . $client->avatar;
                        }
                    }

                    // Randomly assign status for demo
                    $statuses = ['warning', 'danger', 'success'];
                    $statusLabels = [
                        'warning' => 'Needs Meal Plan',
                        'danger' => 'Missing Workout',
                        'success' => 'On Track'
                    ];
                    $randomStatus = $statuses[array_rand($statuses)];

                    // For real implementation, you would check actual client data
                    // to determine which clients need attention
                    ?>
                    <a href="/trainer/customers/profile?id=<?= $client->id ?>" class="client-list-item">
                        <img src="<?= $avatarPath ?>" alt="<?= htmlspecialchars($client->fname) ?>" class="client-avatar">
                        <div class="client-info">
                            <h3 class="client-name"><?= htmlspecialchars($client->fname . ' ' . $client->lname) ?></h3>
                            <div class="client-meta">
                                <span class="client-tag <?= $randomStatus ?>"><?= $statusLabels[$randomStatus] ?></span>
                            </div>
                        </div>
                        <div class="client-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6"></path>
                            </svg>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>