<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

$height = (float)htmlspecialchars($_POST['height']);
$weight = (float)htmlspecialchars($_POST['weight']);
$age = (float)htmlspecialchars($_POST['age']);

$bmi = $weight / (($height / 100) ** 2);
$bmi = round($bmi, 2);


$BMI_CLASSIFICATION = [
    [
        "type" => "Severe Thinness",
        "range" => [0, 16],
        "instruction" => "It’s crucial to seek immediate medical advice to identify any underlying conditions contributing to your low BMI. Focus on consuming calorie-dense, nutrient-rich foods such as whole grains, healthy fats, lean proteins, and dairy. Work with a healthcare provider to create a tailored plan for gradual weight gain, and include light exercises to improve muscle mass and overall health.",
        "bad" => true
    ],
    [
        "type" => "Moderate Thinness",
        "range" => [16, 17],
        "instruction" => "Consult a healthcare professional to address potential health concerns and create a balanced nutritional plan. Prioritize foods rich in calories and essential nutrients like proteins, vitamins, and minerals. Aim for steady weight gain through regular meals and healthy snacks, and consider incorporating light strength exercises to build muscle.",
        "bad" => true
    ],
    [
        "type" => "Mild Thinness",
        "range" => [17, 18.5],
        "instruction" => "Focus on maintaining a healthy weight gain by eating a balanced diet with calorie-dense and nutrient-rich foods. Incorporate regular meals, healthy snacks, and possibly supplements if advised by a healthcare provider. Include moderate exercise to enhance muscle mass, and track your progress to ensure you're moving toward a normal BMI range.",
        "bad" => true
    ],
    [
        "type" => "Normal",
        "range" => [18.5, 25],
        "instruction" => "Maintain your healthy weight by continuing to eat a balanced diet, staying physically active, and keeping hydrated. Engage in regular health checkups to monitor your well-being and prevent any weight fluctuations. A mix of cardio and strength training can help you sustain your fitness and overall health.",
        "bad" => false
    ],
    [
        "type" => "Overweight",
        "range" => [25, 30],
        "instruction" => "Adopt healthier lifestyle habits, such as reducing the consumption of sugary and fatty foods and increasing your intake of fruits, vegetables, and lean proteins. Regular physical activity, including cardio and strength training, can help manage weight effectively. Monitor your progress consistently and consult a healthcare provider for personalized advice.",
        "bad" => true
    ],
    [
        "type" => "Obese Class I",
        "range" => [30, 35],
        "instruction" => "Focus on sustainable weight loss through a calorie-controlled diet rich in whole, unprocessed foods, along with regular physical activity. Behavioral changes, like mindful eating and reducing sedentary habits, are key. Seek support from a healthcare professional or dietitian to create a structured plan that aligns with your health needs.",
        "bad" => true
    ],
    [
        "type" => "Obese Class II",
        "range" => [35, 40],
        "instruction" => "Collaborate with healthcare professionals to develop a comprehensive weight-loss strategy, including dietary changes, increased physical activity, and behavior modification. Start with low-impact exercises to reduce the risk of injury and gradually increase intensity. Regular medical monitoring is essential to ensure safe and effective progress.",
        "bad" => true
    ],
    [
        "type" => "Obese Class III",
        "range" => [40, 99999],
        "instruction" => "Immediate medical intervention is necessary to address potential health risks associated with severe obesity. Work closely with a healthcare team to explore structured weight-loss programs, including dietary plans, physical activity, and possibly medical or surgical interventions. Commit to long-term lifestyle changes for gradual and sustainable weight reduction.",
        "bad" => true
    ],
];

$category = array_search(true, array_map(function ($item) use ($bmi) {
    return $bmi >= $item['range'][0] && $bmi < $item['range'][1];
}, $BMI_CLASSIFICATION));

$category = $BMI_CLASSIFICATION[$category];

$pageConfig = [
    "title" => "BMI Calculator Result",
    "styles" => ["./bmi.css"],
    "titlebar" => [
        "back_url" => "/rat/bmi/index.php",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<main id="result">
    <h1 class="alt"><?= $bmi ?></h1>
    <input type="range" value="<?php echo $bmi ?>" disabled min="10" max="50">
    <h2 class="<?= $category['bad'] ? "bad" : "normal" ?>"><?= $category['type'] ?></h2>
    <p class="paragraph"><?= $category['instruction'] ?></p>
    <a target="_blank" href="https://www.who.int/europe/news-room/fact-sheets/item/a-healthy-lifestyle---who-recommendations" class="article-link">Discover the Facts About BMI and Your Health
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
        </svg>
    </a>
    <form action="save_process.php" method="post">
        <input type="hidden" name="bmi" value="<?php echo $bmi ?>">
        <input type="hidden" name="weight" value="<?php echo $weight ?>">
        <input type="hidden" name="height" value="<?php echo $height ?>">
        <input type="hidden" name="gender" value="<?php echo $age ?>">
        <button class="btn">Save Record</button>
    </form>
    <a href="/rat/bmi/index.php" class="btn secondary">Calculate Again</a>

</main>
<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>