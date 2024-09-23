<?php

$title = isset($pageTitle) ? $pageTitle : null;
$sidebarActive = isset($sidebarActive) ? $sidebarActive : null;

$pageConfig = [
    "title" => "Admin" . ($title ? " | " . $title : ""),
    "sidebar" => [
        "title" => "Admin",
        "links" => [
            ["text" => "Home", "href" => "/staff/admin/index.php"],
            ["text" => "Staff Credentials", "href" => "/staff/admin/credentials/index.php"],
        ],
        "active" => $sidebarActive
    ],
];
