<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;


$facts = [
    [
        "title" => "Welcome to GYMRAT",
        "description" => "We'll guide you through a personalized fitness journey that fits your lifestyle and goals. Let's get started!",
        "image" => "./fact0.png"
    ],
    [
        "title" => "Track Your Workouts",
        "description" => "GYMRAT helps you log and monitor every exercise, set, and rep with ease. Keep your workout history at your fingertips.",
        "image" => "./fact1.png"
    ],
    [
        "title" => "Monitor Your Progress",
        "description" => "Watch your strength and fitness improve over time with detailed progress tracking, charts, and performance metrics.",
        "image" => "./fact2.png"
    ],
    [
        "title" => "Connect With Your Gym",
        "description" => "Stay updated with your gym's schedule, book classes, and receive important announcements directly through the app.",
        "image" => "./fact3.png"
    ]
];

$currentFact = 1;
if (isset($_GET['i']) && $_GET['i'] > 0 && $_GET['i'] < count($facts) + 1) {
    $currentFact = htmlspecialchars($_GET['i']);
}

$pageConfig = [
    "title" => "Onboarding Facts",
    "styles" => ["/rat/styles/auth.css", "./facts.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../includes/header.php";
?>

<main class="fact" style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, #000 100%), url(<?= $facts[$currentFact - 1]['image'] ?>);">
    <div class="fact-content">
        <h1><?= $facts[$currentFact - 1]['title'] ?></h1>
        <p class="paragraph"><?= $facts[$currentFact - 1]['description'] ?></p>
        <div class="actions">
            <?php if ($currentFact > 1): ?>
                <a href="../facts?i=<?= $currentFact - 1 ?>" class="move-button">Previous</a>
            <?php endif; ?>
            <?php if ($currentFact < count($facts)): ?>
                <a href="../facts?i=<?= $currentFact + 1 ?>" class="move-button next">Next</a>
            <?php endif; ?>
            <?php if ($currentFact == count($facts)): ?>
                <a href="../" class="move-button next">Finish</a>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require_once "../../includes/footer.php" ?>