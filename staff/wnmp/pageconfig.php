<?php

$title = isset($pageTitle) ? $pageTitle : null;
$sidebarActive = isset($sidebarActive) ? $sidebarActive : null;

$pageConfig = [
    "title" => ($title ? $title . " | "  : "") . "Workout & Meal Plan Manager",
    "sidebar" => [
        "title" => "Workout & Meal Plan Manager",
        "links" => [
            ["text" => "Home", "href" => "/staff/wnmp/index.php"],
            ["text" => "Exercises", "href" => "/staff/wnmp/exercises/index.php"],
            ["text" => "Workouts", "href" => "/staff/wnmp/workouts/index.php"],
            ["text" => "Meals", "href" => "/staff/wnmp/meals/index.php"],
            ["text" => "Meal Plans", "href" => "/staff/wnmp/meal-plans/index.php"],
        ],
        "active" => $sidebarActive
    ],
];
