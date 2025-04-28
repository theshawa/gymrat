<?php

$title = isset($pageTitle) ? $pageTitle : null;
$sidebarActive = isset($sidebarActive) ? $sidebarActive : null;
$styles = isset($pageStyles) ? $pageStyles : [];

$pageConfig = [
    "title" => ($title ?  $title . " | " : "") . "Equipment Manager",
    "sidebar" => [
        "title" => "Equipment Manager",
        "links" => [
            ["text" => "Home", "href" => "/staff/eq/index.php"],
            ["text" => "Equipments", "href" => "/staff/eq/equipments/index.php"],
            ["text" => "Log Records", "href" => "/staff/eq/log-records/index.php"],
        ],
        "active" => $sidebarActive
    ],
    "styles" => $styles
];
