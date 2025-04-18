<?php

$title = isset($pageTitle) ? $pageTitle : null;
$sidebarActive = isset($sidebarActive) ? $sidebarActive : null;
$styles = isset($pageStyles) ? $pageStyles : [];

$pageConfig = [
    "title" => ($title ?  $title . " | "  : "") . "Admin",
    "sidebar" => [
        "title" => "Admin",
        "links" => [
            ["text" => "Home", "href" => "/staff/admin/index.php"],
            ["text" => "Membership Plans", "href" => "/staff/admin/membership-plans/index.php"],
            ["text" => "Rats", "href" => "/staff/admin/rats/index.php"],
            ["text" => "Trainers", "href" => "/staff/admin/trainers/index.php"],
            ["text" => "Announcements", "href" => "/staff/admin/announcements/index.php"],
            ["text" => "Complaints", "href" => "/staff/admin/complaints/index.php"],
            ["text" => "Finance", "href" => "/staff/admin/finance/index.php"],
            ["text" => "Staff Credentials", "href" => "/staff/admin/credentials/index.php"],
            ["text" => "Settings", "href" => "/staff/admin/settings/index.php"],
        ],
        "active" => $sidebarActive
    ],
    "styles" => $styles
];
