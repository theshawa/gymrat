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
require_once "../../../../db/models/CustomerInitialData.php";
require_once "../../../../db/models/BmiRecord.php";
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

// Get customer initial data
$initialData = new CustomerInitialData();
try {
    $initialData->get_by_id($customerId);
    $hasInitialData = true;
} catch (Exception $e) {
    $hasInitialData = false;
}

// Get BMI records for the customer
$bmiRecordModel = new BmiRecord();
$bmiRecords = $bmiRecordModel->get_all_of_user($customerId);

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

// Prepare BMI chart data
$bmiChartData = [
    'labels' => [],
    'values' => [],
    'weights' => []
];

if (!empty($bmiRecords)) {
    usort($bmiRecords, function ($a, $b) {
        return $a->created_at <=> $b->created_at;
    });

    foreach ($bmiRecords as $record) {
        $bmiChartData['labels'][] = $record->created_at->format('c');
        $bmiChartData['values'][] = $record->bmi;
        $bmiChartData['weights'][] = $record->weight;
    }
}

// Calculate weight change
$weightChange = 0;
$weightChangeTrend = 'stable';
$weightChangeTrendIcon = '↔️';

if (count($bmiChartData['weights']) >= 2) {
    $firstWeight = $bmiChartData['weights'][0];
    $lastWeight = $bmiChartData['weights'][count($bmiChartData['weights']) - 1];
    $weightChange = $lastWeight - $firstWeight;

    if ($weightChange < 0) {
        $weightChangeTrend = 'decreased';
        $weightChangeTrendIcon = '↘️';
    } elseif ($weightChange > 0) {
        $weightChangeTrend = 'increased';
        $weightChangeTrendIcon = '↗️';
    }
}

$pageConfig = [
    "title" => "Progress Analytics",
    "styles" => [
        "./progress_analytics.css"
    ],
    "scripts" => [
        "https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js",
        "https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js",
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
        <h2><?= htmlspecialchars($customer['fname']) ?>'s Progress</h2>
        <p class="subtitle">Analytics based on physical measurements and assessments</p>
    </div>

    <!-- Customer Initial Data -->
    <?php if ($hasInitialData): ?>
        <div class="initial-data-card">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user-circle-2">
                    <path d="M18 20a6 6 0 0 0-12 0" />
                    <circle cx="12" cy="10" r="4" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                Initial Profile Data
            </h3>
            <div class="initial-data-grid">
                <div class="data-item">
                    <span class="label">Gender:</span>
                    <span class="value"><?= htmlspecialchars(ucfirst($initialData->gender)) ?></span>
                </div>
                <div class="data-item">
                    <span class="label">Age:</span>
                    <span class="value"><?= htmlspecialchars($initialData->age) ?> years</span>
                </div>
                <div class="data-item">
                    <span class="label">Height:</span>
                    <span class="value"><?= htmlspecialchars($initialData->height) ?> cm</span>
                </div>
                <div class="data-item">
                    <span class="label">Initial Weight:</span>
                    <span class="value"><?= htmlspecialchars($initialData->weight) ?> kg</span>
                </div>
                <div class="data-item">
                    <span class="label">Goal:</span>
                    <span class="value">
                        <?php
                        $goalDisplay = $initialData->goal;
                        switch ($initialData->goal) {
                            case 'weight_loss':
                                $goalDisplay = 'Weight Loss';
                                break;
                            case 'weight_gain':
                                $goalDisplay = 'Weight Gain';
                                break;
                            case 'muscle_gain':
                                $goalDisplay = 'Muscle Gain';
                                break;
                            case 'maintain':
                                $goalDisplay = 'Maintain Weight';
                                break;
                            default:
                                $goalDisplay = ucfirst(str_replace('_', ' ', $initialData->goal));
                        }
                        echo htmlspecialchars($goalDisplay);
                        ?>
                    </span>
                </div>
                <div class="data-item">
                    <span class="label">Activity Level:</span>
                    <span
                        class="value"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $initialData->physical_activity_level))) ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- BMI and Weight Progress -->
    <?php if (!empty($bmiRecords)): ?>
        <div class="weight-progress-card expandable-card">
            <div class="card-header">
                <div class="trend-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-activity">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                        Weight &<br> BMI Progress
                    </h3>
                    <?php if (count($bmiChartData['weights']) >= 2): ?>
                        <div class="trend-indicator <?= $weightChangeTrend ?>" style="margin-left: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide <?= $weightChangeTrend === 'decreased' ? 'lucide-trending-down' : ($weightChangeTrend === 'increased' ? 'lucide-trending-up' : 'lucide-minus') ?>">
                                <?php if ($weightChangeTrend === 'decreased'): ?>
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7" />
                                    <polyline points="16 17 22 17 22 11" />
                                <?php elseif ($weightChangeTrend === 'increased'): ?>
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                                    <polyline points="16 7 22 7 22 13" />
                                <?php else: ?>
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                <?php endif; ?>
                            </svg>
                            <?= abs($weightChange) ?> kg <?= $weightChangeTrend ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="expand-btn" aria-label="Expand chart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-expand">
                        <path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8" />
                        <path d="M3 16.2V21m0 0h4.8M3 21l6-6" />
                        <path d="M21 7.8V3m0 0h-4.8M21 3l-6 6" />
                        <path d="M3 7.8V3m0 0h4.8M3 3l6 6" />
                    </svg>
                </button>
            </div>
            <div class="card-content">
                <div class="chart-container">
                    <canvas id="bmiChart"></canvas>
                </div>
                <div class="bmi-record-list" aria-label="BMI Records">
                    <?php foreach ($bmiRecords as $index => $record): ?>
                        <div class="bmi-record-item">
                            <div class="record-date"><?= htmlspecialchars($record->created_at->format('M d, Y')) ?></div>
                            <div class="record-value">
                                <div class="record-bmi">
                                    <strong>BMI:</strong> <?= htmlspecialchars(number_format($record->bmi, 1)) ?>
                                    <?php
                                    $bmiCategory = '';
                                    if ($record->bmi < 18.5)
                                        $bmiCategory = 'Underweight';
                                    else if ($record->bmi < 25)
                                        $bmiCategory = 'Normal';
                                    else if ($record->bmi < 30)
                                        $bmiCategory = 'Overweight';
                                    else
                                        $bmiCategory = 'Obese';
                                    ?>
                                    <span class="bmi-category <?= strtolower($bmiCategory) ?>"><?= $bmiCategory ?></span>
                                </div>
                                <div class="record-weight"><strong>Weight:</strong> <?= htmlspecialchars($record->weight) ?> kg
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="chart-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-info">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    <span>For better readability, only key data points are displayed. Hover over the chart to see all
                        values.</span>
                </div>
                <div class="bmi-legend">
                    <div class="legend-item">
                        <span class="color-indicator" style="background-color: #ffcc00;"></span>
                        <span class="legend-text">Underweight: BMI < 18.5</span>
                    </div>
                    <div class="legend-item">
                        <span class="color-indicator" style="background-color: #42b34d;"></span>
                        <span class="legend-text">Normal: BMI 18.5-24.9</span>
                    </div>
                    <div class="legend-item">
                        <span class="color-indicator" style="background-color: #ff9900;"></span>
                        <span class="legend-text">Overweight: BMI 25-29.9</span>
                    </div>
                    <div class="legend-item">
                        <span class="color-indicator" style="background-color: #ff3300;"></span>
                        <span class="legend-text">Obese: BMI ≥ 30</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Trainer Assessment Data -->
    <?php if (empty($progressData) && empty($bmiRecords)): ?>
        <div class="no-data-message">
            <div class="no-data-icon">📊</div>
            <h3>No Progress Data Yet</h3>
            <p>There is no progress data available for this client yet. Encourage them to use the BMI calculator or add your
                own assessment.</p>
            <a href="../add-log/?id=<?= $customerId ?>" class="btn add-progress-btn">Add Progress Feedback</a>
        </div>
    <?php elseif (!empty($progressData)): ?>

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
    const bmiChartData = <?= json_encode($bmiChartData) ?>;
</script>

<?php require_once "../../../includes/navbar.php" ?>
<?php require_once "../../../includes/footer.php" ?>