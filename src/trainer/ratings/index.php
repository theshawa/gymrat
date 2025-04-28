<?php
// File path: src/trainer/ratings/index.php
require_once "../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../db/Database.php";

// Get trainer ID from session
$trainerId = $_SESSION['auth']['id'] ?? 0;

// Get database connection
$conn = Database::get_conn();

// Check if trainer_ratings table exists, create it if it doesn't
try {
    $checkTableSql = "SHOW TABLES LIKE 'trainer_ratings'";
    $checkTableStmt = $conn->prepare($checkTableSql);
    $checkTableStmt->execute();

    if ($checkTableStmt->rowCount() === 0) {
        // Create the table if it doesn't exist
        $createTableSql = "CREATE TABLE `trainer_ratings` (
            `id` int NOT NULL AUTO_INCREMENT,
            `trainer_id` int NOT NULL,
            `customer_id` int NOT NULL,
            `rating` int NOT NULL,
            `review` text,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `trainer_ratings_trainer_id` (`trainer_id`),
            KEY `trainer_ratings_customer_id` (`customer_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

        $createTableStmt = $conn->prepare($createTableSql);
        $createTableStmt->execute();

        // Add sample ratings if this is a new table
        $sampleRatingsSql = "INSERT INTO `trainer_ratings` 
            (trainer_id, customer_id, rating, review, created_at)
        VALUES
            (1, 1, 5, 'Excellent trainer! Really helped me achieve my fitness goals.', DATE_SUB(NOW(), INTERVAL 30 DAY)),
            (1, 2, 4, 'Good knowledge and motivating sessions.', DATE_SUB(NOW(), INTERVAL 25 DAY)),
            (1, 3, 5, 'Very professional and knowledgeable.', DATE_SUB(NOW(), INTERVAL 20 DAY)),
            (1, 4, 5, 'The best trainer I have ever had!', DATE_SUB(NOW(), INTERVAL 15 DAY)),
            (1, 5, 4, 'Great sessions and helpful advice.', DATE_SUB(NOW(), INTERVAL 10 DAY)),
            (1, 6, 5, 'Helped me transform my fitness level completely.', DATE_SUB(NOW(), INTERVAL 5 DAY))";

        $sampleRatingsStmt = $conn->prepare($sampleRatingsSql);
        $sampleRatingsStmt->execute();
    }
} catch (Exception $e) {
    // Silently handle errors
}

// Get trainer info
$trainerInfo = [];
try {
    $trainerSql = "SELECT id, fname, lname, avatar, rating, review_count, bio FROM trainers WHERE id = :trainer_id";
    $trainerStmt = $conn->prepare($trainerSql);
    $trainerStmt->bindValue(':trainer_id', $trainerId);
    $trainerStmt->execute();
    $trainerInfo = $trainerStmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Silently handle errors
}

// Calculate rating summary - both from trainer table and actual ratings
$ratingData = [
    'average' => $trainerInfo['rating'] ?? 0,
    'total' => $trainerInfo['review_count'] ?? 0,
    'distribution' => [
        5 => 0,
        4 => 0,
        3 => 0,
        2 => 0,
        1 => 0
    ]
];

// Get actual ratings data
try {
    // Get rating distribution
    $distributionSql = "SELECT rating, COUNT(*) as count FROM trainer_ratings 
                        WHERE trainer_id = :trainer_id 
                        GROUP BY rating 
                        ORDER BY rating DESC";
    $distributionStmt = $conn->prepare($distributionSql);
    $distributionStmt->bindValue(':trainer_id', $trainerId);
    $distributionStmt->execute();

    $distribution = $distributionStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($distribution as $row) {
        $ratingData['distribution'][$row['rating']] = $row['count'];
    }

    // Get total ratings
    $totalSql = "SELECT COUNT(*) as total, AVG(rating) as average FROM trainer_ratings WHERE trainer_id = :trainer_id";
    $totalStmt = $conn->prepare($totalSql);
    $totalStmt->bindValue(':trainer_id', $trainerId);
    $totalStmt->execute();

    $totals = $totalStmt->fetch(PDO::FETCH_ASSOC);

    // If we have actual ratings, use those numbers instead of the stored ones
    if ($totals['total'] > 0) {
        $ratingData['total'] = $totals['total'];
        $ratingData['average'] = round($totals['average'], 1);
    }
} catch (Exception $e) {
    // Silently handle errors
}

// Calculate the maximum distribution count for scaling bars
$maxDistribution = max($ratingData['distribution']) ?: 1; // Avoid division by zero


$pageConfig = [
    "title" => "My Ratings",
    "styles" => [
        "./ratings.css" // CSS file to be created
    ],
    "scripts" => [
        "./ratings.js" // Add the JavaScript file
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
        "title" => "MY RATINGS"
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main class="ratings-page">
    <!-- Rating Summary Section -->
    <div class="rating-card">
        <div class="rating-distribution">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <div class="distribution-row">
                    <div class="star-level"><?= $i ?></div>
                    <div class="distribution-bar-container">
                        <div class="distribution-bar"
                            style="width: <?= ($ratingData['distribution'][$i] / $maxDistribution) * 100 ?>%;"></div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="rating-score">
            <div class="score"><?= number_format($ratingData['average'], 1) ?></div>
            
            <div class="stars-container">
                <?php
                // Calculate full stars, partial stars, and empty stars
                $rating = $ratingData['average'];
                $fullStars = floor($rating);
                $partialStar = $rating - $fullStars > 0;
                $partialStarPercentage = ($rating - $fullStars) * 100;
                $emptyStars = 5 - $fullStars - ($partialStar ? 1 : 0);
                
                // Output full stars
                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<span class="star filled">★</span>';
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
                    echo '<span class="star">☆</span>';
                }
                ?>
            </div>
            
            <div class="total-reviews"><?= number_format($ratingData['total']) ?> reviews</div>
        </div>
    </div>

    <!-- Reviews Section -->
    <?php
    // Get recent reviews with customer info
    $reviews = [];
    try {
        $reviewsSql = "SELECT tr.*, c.fname, c.lname, c.avatar
                      FROM trainer_ratings tr
                      JOIN customers c ON tr.customer_id = c.id
                      WHERE tr.trainer_id = :trainer_id
                      ORDER BY tr.created_at DESC
                      LIMIT 20";
        $reviewsStmt = $conn->prepare($reviewsSql);
        $reviewsStmt->bindValue(':trainer_id', $trainerId);
        $reviewsStmt->execute();

        $reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Silently handle errors
    }

    if (!empty($reviews)):
    ?>
        <div class="reviews-section">
            <h3>Recent Reviews</h3>

            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <?php
                        // Correctly handle the avatar path
                        $reviewerAvatarPath = '/uploads/default-images/default-avatar.png'; // Default

                        if (!empty($review['avatar'])) {
                            // Check if avatar already starts with "/uploads/"
                            if (strpos($review['avatar'], '/uploads/') === 0) {
                                $reviewerAvatarPath = $review['avatar'];
                            }
                            // Check if it starts with "uploads/"
                            else if (strpos($review['avatar'], 'uploads/') === 0) {
                                $reviewerAvatarPath = '/' . $review['avatar'];
                            }
                            // Otherwise, assume it's in "customer-avatars/"
                            else {
                                $reviewerAvatarPath = '/uploads/' . $review['avatar'];
                            }
                        }
                        ?>
                        <div class="review-card-header">
                            <a href="../customers/profile?id=<?= $review['customer_id'] ?>" class="review-avatar-link">
                                <img src="<?= $reviewerAvatarPath ?>" alt="<?= htmlspecialchars($review['fname']) ?>"
                                    class="review-avatar">
                            </a>
                            <div class="review-user-info">
                                <div class="review-user-name">
                                    <?= htmlspecialchars($review['fname'] . ' ' . $review['lname']) ?>
                                </div>
                                <div class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <span class="star-icon filled">★</span>
                                        <?php else: ?>
                                            <span class="star-icon">☆</span>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($review['review'])): ?>
                            <div class="review-message">
                                <p><?= htmlspecialchars($review['review']) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="review-footer">
                            <div class="review-date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="calendar-icon">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= date('M d, Y', strtotime($review['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="no-reviews">
            <p>No reviews yet. Keep up the good work with your clients!</p>
        </div>
    <?php endif; ?>
</main>

<style>
/* Add additional star rating styles */
.stars-container {
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 5px 0;
}

.star-partial-container {
    position: relative;
    display: inline-block;
    font-size: 18px;
    line-height: 1;
    height: 18px;
}

.star-empty {
    color: #e0e0e0;
}

.star-filled {
    position: absolute;
    top: 0;
    left: 0;
    color: #ffc107;
    overflow: hidden;
    height: 100%;
    white-space: nowrap;
}
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>