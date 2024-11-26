<?php
$pageConfig = [
    "title" => "Onboarding Facts",
    "styles" => ["/rat/styles/auth.css", "./facts.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "need_auth" => true,
];

require_once "../../includes/header.php";

$facts = [
    [
        "title" => "Find your optimal workout time",
        "description" => "Identify your best workout times based on your schedule and energy levels for optimal performance.",
        "image" => "./fact1.png"
    ],
    [
        "title" => "Start your journey towards a more active lifestyle",
        "description" => "Start your fitness journey with personalized plans, progress tracking, and achievable goals. Begin today and stay motivated.",
        "image" => "./fact2.png"
    ],
    [
        "title" => "Find nutrition tips that fit your lifestyle",
        "description" => "Get personalized nutrition tips to make healthier food choices and achieve your fitness goals effectively.",
        "image" => "./fact3.png"
    ]
];

$currentFact = 1;
if (isset($_GET['i']) && $_GET['i'] > 0 && $_GET['i'] < count($facts) + 1) {
    $currentFact = htmlspecialchars($_GET['i']);
}

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