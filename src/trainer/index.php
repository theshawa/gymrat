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

// Get all clients and categorize them by attention status
$recentClients = [];
if (!empty($activeCustomers)) {
    // Separate clients into those needing attention and those on track
    $clientsNeedingAttention = [];
    $clientsOnTrack = [];
    
    foreach ($activeCustomers as $client) {
        $needsAttention = false;
        $attentionReason = '';
        
        if ($client->workout === null) {
            $needsAttention = true;
            $attentionReason = 'Needs Workout Plan';
        } elseif ($client->meal_plan === null) {
            $needsAttention = true;
            $attentionReason = 'Needs Meal Plan';
        }
        
        if ($needsAttention) {
            $client->attention_reason = $attentionReason;
            $clientsNeedingAttention[] = $client;
        } else {
            $client->attention_reason = 'On Track';
            $clientsOnTrack[] = $client;
        }
    }
    
    // Combine the lists with clients needing attention first
    $combinedClients = array_merge($clientsNeedingAttention, $clientsOnTrack);
    
    // Take up to 10 clients to display (prioritizing those who need attention)
    $recentClients = array_slice($combinedClients, 0, 10);
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
            
            <!-- Enhanced star rating visualization -->
            <div class="star-rating-small">
                <?php
                // Calculate full stars, partial stars, and empty stars
                $rating = $ratingData['avg_rating'];
                $fullStars = floor($rating);
                $partialStar = $rating - $fullStars > 0;
                $partialStarPercentage = ($rating - $fullStars) * 100;
                $emptyStars = 5 - $fullStars - ($partialStar ? 1 : 0);
                
                // Output full stars
                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<span class="star full">★</span>';
                }
                
                // Output partial star if needed
                if ($partialStar) {
                    echo '<div class="star-partial-container">';
                    echo '<span class="star-empty">☆</span>';
                    echo '<span class="star-filled" style="width: ' . $partialStarPercentage . '%;">★</span>';
                    echo '</div>';
                }
                
                // Output empty stars
                for ($i = 0; $i < $emptyStars; $i++) {
                    echo '<span class="star empty">☆</span>';
                }
                ?>
            </div>
            
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

            <a href="/trainer/announcements" class="feature-card">
                <div class="feature-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                        class="lucide lucide-megaphone">
                        <path d="m3 11 18-5v12L3 13"/>
                        <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                    </svg>
                </div>
                <div class="feature-card-content">
                    <div class="feature-card-title">Announcements</div>
                    <p class="feature-card-description">Create and manage announcements for your clients</p>
                </div>
                <svg class="feature-card-decoration" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                    viewBox="0 0 24 24" fill="currentColor" stroke="none"
                    class="lucide lucide-megaphone">
                    <path d="m3 11 18-5v12L3 13"/>
                    <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
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
                <h2 class="section-title">Client Status Overview</h2>
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
                    <div class="client-list-title">Client Status</div>
                </div>

                <?php require_once "../uploads.php"; ?>
                <?php foreach ($recentClients as $client):
                    // Get default avatar path
                    $avatar = get_file_url($client->avatar);

                    // Determine status based on client attention reason
                    $statusType = 'success';
                    $statusReason = $client->attention_reason;
                    
                    // Set appropriate status class
                    if ($statusReason === 'Needs Workout Plan') {
                        $statusType = 'danger';
                    } elseif ($statusReason === 'Needs Meal Plan') {
                        $statusType = 'warning';
                    } else {
                        $statusType = 'success';
                    }

                    // For real implementation, you would check actual client data
                    // to determine which clients need attention
                    ?>
                    <a href="/trainer/customers/profile?id=<?= $client->id ?>" class="client-list-item">
                        <img src="<?= $avatar ?>" alt="<?= htmlspecialchars($client->fname) ?>" class="client-avatar">
                        <div class="client-info">
                            <h3 class="client-name"><?= htmlspecialchars($client->fname . ' ' . $client->lname) ?></h3>
                            <div class="client-meta">
                                <span class="client-tag <?= $statusType ?>"><?= $statusReason ?></span>
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

<style>
/* Star rating with partial stars for the dashboard */
.star-rating-small {
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 3px 0;
}

.star-rating-small .star {
    font-size: 16px;
    color: #444;
}

.star-rating-small .star.full {
    color: #ffc107;
}

.star-rating-small .star.empty {
    color: #444;
}

/* Partial star styling */
.star-rating-small .star-partial-container {
    position: relative;
    display: inline-block;
    font-size: 16px;
    line-height: 1;
    height: 16px;
}

.star-rating-small .star-empty {
    color: #444;
}

.star-rating-small .star-filled {
    position: absolute;
    top: 0;
    left: 0;
    color: #ffc107;
    overflow: hidden;
    height: 100%;
    white-space: nowrap;
}
</style>

<?php require_once "./includes/navbar.php" ?>
<?php require_once "./includes/footer.php" ?>