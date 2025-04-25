<?php
// File: src/trainer/customers/profile/progress_analytics.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If customer ID is missing, redirect back to customer list
if (!$customerId) {
    header("Location: ../../");
    exit;
}

// Get database connection
require_once "../../../../db/Database.php";
$conn = Database::get_conn();

// Get customer details
$customerSql = "SELECT fname, lname FROM customers WHERE id = :customer_id LIMIT 1";
$customerStmt = $conn->prepare($customerSql);
$customerStmt->bindValue(':customer_id', $customerId);
$customerStmt->execute();
$customer = $customerStmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    die("Customer not found");
}

// Get progress data from customer_progress table
$progressSql = "SELECT * FROM customer_progress 
                WHERE customer_id = :customer_id 
                ORDER BY created_at ASC";
$progressStmt = $conn->prepare($progressSql);
$progressStmt->bindValue(':customer_id', $customerId);
$progressStmt->execute();
$progressData = $progressStmt->fetchAll(PDO::FETCH_ASSOC);

// Process data for charts
$progressByType = [
    'well_done' => 0,
    'try_harder' => 0
];

$progressByMonth = [];
$lastSixMonthsFeedback = [];

// Group data by month and performance type
foreach ($progressData as $item) {
    $month = date('M Y', strtotime($item['created_at']));
    $type = $item['performance_type'];

    // Count by performance type
    $progressByType[$type]++;

    // Group by month
    if (!isset($progressByMonth[$month])) {
        $progressByMonth[$month] = [
            'well_done' => 0,
            'try_harder' => 0,
            'total' => 0
        ];
    }

    $progressByMonth[$month][$type]++;
    $progressByMonth[$month]['total']++;

    // Get last 6 entries for recent feedback
    if (count($lastSixMonthsFeedback) < 6) {
        $lastSixMonthsFeedback[] = [
            'message' => $item['message'],
            'type' => $item['performance_type'],
            'date' => date('M d, Y', strtotime($item['created_at']))
        ];
    }
}

// Sort by month (oldest to newest)
ksort($progressByMonth);

// Take last 6 months for chart (or all if less than 6)
$months = array_keys($progressByMonth);
$totalMonths = count($months);
$chartMonths = array_slice($months, max(0, $totalMonths - 6), 6);

// Prepare data for chart
$chartData = [
    'labels' => [],
    'well_done' => [],
    'try_harder' => [],
    'ratio' => []
];

foreach ($chartMonths as $month) {
    $chartData['labels'][] = $month;
    $chartData['well_done'][] = $progressByMonth[$month]['well_done'];
    $chartData['try_harder'][] = $progressByMonth[$month]['try_harder'];

    // Calculate the ratio of well_done to total (as percentage)
    $total = $progressByMonth[$month]['total'];
    $ratio = $total > 0 ? round(($progressByMonth[$month]['well_done'] / $total) * 100) : 0;
    $chartData['ratio'][] = $ratio;
}

// Calculate overall progress ratio
$totalEntries = array_sum($progressByType);
$successRatio = $totalEntries > 0 ? round(($progressByType['well_done'] / $totalEntries) * 100) : 0;

// Calculate trend (improved, declined, stable)
$trend = 'stable';
$trendIcon = '↔️';
if (count($chartData['ratio']) >= 2) {
    $firstHalf = array_slice($chartData['ratio'], 0, floor(count($chartData['ratio']) / 2));
    $secondHalf = array_slice($chartData['ratio'], floor(count($chartData['ratio']) / 2));

    $firstAvg = count($firstHalf) > 0 ? array_sum($firstHalf) / count($firstHalf) : 0;
    $secondAvg = count($secondHalf) > 0 ? array_sum($secondHalf) / count($secondHalf) : 0;

    if ($secondAvg - $firstAvg > 5) {
        $trend = 'improved';
        $trendIcon = '↗️';
    } elseif ($firstAvg - $secondAvg > 5) {
        $trend = 'declined';
        $trendIcon = '↘️';
    }
}

$pageConfig = [
    "title" => "Progress Analytics",
    "styles" => [
        "./progress_analytics.css"
    ],
    "scripts" => [
        "https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js",
        "./progress_analytics.js"
    ],
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../?id=" . $customerId,
        "title" => "PROGRESS ANALYTICS"
    ]
];

require_once "../../../includes/header.php";
require_once "../../../includes/titlebar.php";
?>

<main class="progress-analytics-page">
    <div class="client-header">
        <h2><?= htmlspecialchars($customer['fname'] . ' ' . $customer['lname']) ?>'s Progress</h2>
        <p class="subtitle">Analytics based on your feedback and assessments</p>
    </div>

    <?php if (empty($progressData)): ?>
        <div class="no-data-message">
            <div class="no-data-icon">📊</div>
            <h3>No Progress Data Yet</h3>
            <p>You haven't recorded any progress feedback for this client yet. Start adding progress records to see
                analytics.</p>
            <a href="../add-log/?id=<?= $customerId ?>" class="btn add-progress-btn">Add Progress Feedback</a>
        </div>
    <?php else: ?>
        <!-- Progress Summary Card -->
        <div class="progress-summary-card">
            <div class="summary-item">
                <div class="summary-value"><?= $totalEntries ?></div>
                <div class="summary-label">Total Assessments</div>
            </div>
            <div class="summary-item">
                <div class="summary-value success-color"><?= $progressByType['well_done'] ?></div>
                <div class="summary-label">Well Done</div>
            </div>
            <div class="summary-item">
                <div class="summary-value warning-color"><?= $progressByType['try_harder'] ?></div>
                <div class="summary-label">Try Harder</div>
            </div>
            <div class="summary-item">
                <div
                    class="summary-value <?= $successRatio >= 70 ? 'success-color' : ($successRatio >= 50 ? 'neutral-color' : 'warning-color') ?>">
                    <?= $successRatio ?>%
                </div>
                <div class="summary-label">Success Rate</div>
            </div>
        </div>

        <!-- Progress Trend -->
        <div class="trend-card">
            <div class="trend-header">
                <h3>Performance Trend</h3>
                <div class="trend-indicator <?= $trend ?>">
                    <?= $trendIcon ?>     <?= ucfirst($trend) ?>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="progressChart"></canvas>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="../add-log/?id=<?= $customerId ?>" class="btn add-progress-btn">Add New Progress Feedback</a>
        </div>
    <?php endif; ?>
</main>

<!-- Pass data to JavaScript -->
<script>
    // Chart data
    const progressChartData = <?= json_encode($chartData) ?>;
</script>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>