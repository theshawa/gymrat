<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Settings.php";
$settings = new Settings();
try {
    $settings->get_all();
} catch (\Throwable $th) {
    die("Failed to get contact details: " . $th->getMessage());
}


$pageConfig = [
    "title" => "Your Gym",
    "styles" => ["./gym.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <h1><?= $settings->gym_name ?? "GYMRAT Fitness Centre" ?></h1>
    <p class="paragraph">
        <?= $settings->gym_description ?? "Your fitness journey starts here. Join us today!" ?>
    </p>
    <?php if ($settings->contact_email || $settings->contact_phone): ?>
        <div class="wrapper">
            <h3>Contact</h3>
            <?php if ($settings->contact_email): ?>
                <a href="mailto:<?= $settings->contact_email ?>" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail">
                        <rect width="20" height="16" x="2" y="4" rx="2" />
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                    </svg> <?= $settings->contact_email ?>
                </a>
            <?php endif; ?>

            <?php if ($settings->contact_phone): ?>
                <a href="tel:<?= $settings->contact_phone ?>" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-icon lucide-phone">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg> <?= $settings->contact_phone ?>
                </a>
            <?php endif; ?>

        </div>
    <?php endif; ?>
    <?php if ($settings->gym_address): ?>
        <div class="wrapper">
            <h3>Location</h3>
            <p class="paragraph">
                <?= $settings->gym_address ?>
            </p>
        </div>
    <?php endif; ?>
</main>