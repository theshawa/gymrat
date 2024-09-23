<?php

$title = isset($pageTitle) ? $pageTitle : null;
$sidebarActive = isset($sidebarActive) ? $sidebarActive : null;

$pageConfig = [
    "title" => "Equipment Manager" . ($title ? " | " . $title : ""),
    "sidebar" => [
        "title" => "Equipment Manager",
        "links" => [
            ["text" => "Home", "href" => "/staff/eq/index.php"],
            ["text" => "Equipments", "href" => "/staff/eq/equipments/index.php"],
            ["text" => "Log Records", "href" => "/staff/eq/log-records/index.php"],
        ],
        "active" => $sidebarActive
    ],
];
