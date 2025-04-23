<?php

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

function get_bmi_classification($bmi)
{
    global $BMI_CLASSIFICATION;
    $category =  array_search(true, array_map(function ($item) use ($bmi) {
        return $bmi >= $item['range'][0] && $bmi < $item['range'][1];
    }, $BMI_CLASSIFICATION));
    return $BMI_CLASSIFICATION[$category];
}
