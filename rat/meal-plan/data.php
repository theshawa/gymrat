<?php

$mealPlan = [
    "title" => "Fat Loss Plan",
    "description" => "Calorie deficit while maintaining muscle mass.",
    "usage" => "5 balanced meals per day.",
    "image" => "./images/featured_image.avif",
    "calories" => 1800,
    "meals" => [
        [
            "mealName" => "Lean Start",
            "items" => [
                ["name" => "Scrambled Egg Whites (5)", "calories" => 85, "image" => "./images/a1.png"],
                ["name" => "1 Slice of Whole-Grain Bread", "calories" => 70, "image" => "./images/a2.png"],
                ["name" => "Avocado (1/4)", "calories" => 60, "image" => "./images/a3.png"],
                ["name" => "Black Coffee or Green Tea (unsweetened)", "calories" => 0, "image" => "./images/a4.png"],
            ],
            "totalCalories" => 215,
        ],
        [
            "mealName" => "Light Snack",
            "items" => [
                ["name" => "1 Apple (medium)", "calories" => 95, "image" => "./images/b1.jpg"],
                ["name" => "Almond Butter (1 tbsp)", "calories" => 90, "image" => "./images/b2.png"],
            ],
            "totalCalories" => 185,
        ],
        [
            "mealName" => "High Protein Lunch",
            "items" => [
                ["name" => "Grilled Turkey Breast (120g)", "calories" => 180, "image" => "./images/b3.jpg"],
                ["name" => "Steamed Broccoli (1 cup)", "calories" => 55, "image" => "./images/b4.jpg"],
                ["name" => "Quinoa (1/2 cup cooked)", "calories" => 110, "image" => "./images/b5.jpg"],
                ["name" => "Olive Oil (1 tsp, for drizzle)", "calories" => 40, "image" => "./images/b6.png"],
            ],
            "totalCalories" => 385,
        ],
        [
            "mealName" => "Pre-Workout Snack",
            "items" => [
                ["name" => "Plain Greek Yogurt (1/2 cup, non-fat)", "calories" => 60, "image" => "./images/c1.png"],
                ["name" => "Blueberries (1/2 cup)", "calories" => 40, "image" => "./images/c2.png"],
                ["name" => "Walnuts (5 halves)", "calories" => 65, "image" => "./images/c3.png"],
            ],
            "totalCalories" => 165,
        ],
        [
            "mealName" => "Post-Workout Recovery",
            "items" => [
                ["name" => "Whey Protein Shake (1 scoop with water)", "calories" => 120, "image" => "./images/c4.png"],
                ["name" => "Banana (small)", "calories" => 90, "image" => "./images/c5.png"],
            ],
            "totalCalories" => 210,
        ],
        [
            "mealName" => "Light Dinner",
            "items" => [
                ["name" => "Grilled Shrimp (120g)", "calories" => 140, "image" => "./images/d1.png"],
                ["name" => "Steamed Asparagus (1 cup)", "calories" => 40, "image" => "./images/d2.png"],
                ["name" => "Sweet Potato (1 small, 100g)", "calories" => 90, "image" => "./images/d3.png"],
                ["name" => "Lemon Juice & Spices", "calories" => 0, "image" => "./images/d4.png"],
            ],
            "totalCalories" => 270,
        ],
        [
            "mealName" => "Evening Snack (Optional)",
            "items" => [
                ["name" => "Cucumber Slices (1 cup)", "calories" => 16, "image" => "./images/e1.png"],
                ["name" => "Hummus (2 tbsp)", "calories" => 70, "image" => "./images/e2.png"],
            ],
            "totalCalories" => 86,
        ],
    ],
];
