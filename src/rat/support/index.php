<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Customer.php";
$customer = new Customer();
$customer->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $customer->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

require_once "../../db/models/Trainer.php";
$trainer = new Trainer();
$trainer->fill([
    'id' => $customer->trainer
]);
try {
    $trainer->get_by_id();
} catch (Exception $th) {
    die("Failed to get trainer: " . $th->getMessage());
}

require_once "../../db/models/Settings.php";
$settings = new Settings();
try {
    $settings->get_all();
} catch (\Throwable $th) {
    die("Failed to get contact details: " . $th->getMessage());
}


$pageConfig = [
    "title" => "Contact Support",
    "styles" => ["./support.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
];

$gym_contact = [
    'phone' => '123-456-7890',
    'email' => '',
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <div class="support">
        <h2>Contact Your Trainer</h2>
        <p class="paragraph">
            If you have any questions or need assistance, please reach out to your trainer directly.
        </p>
        <a href="tel:<?= $trainer->phone ?>" class="support-link">Call: <?= $trainer->phone ?></a>
    </div>
    <div class="support">
        <h2>Contact Gym</h2>
        <p class="paragraph">For general inquiries and assistance:</p>
        <div class="inline">
            <?php if ($settings->contact_email): ?>
                <a href="mailto:<?= $settings->contact_email ?>" class="support-link">Email: <?= $settings->contact_email ?></a>
            <?php endif; ?>
            <?php if ($settings->contact_phone): ?>
                <a href="tel:<?= $settings->contact_phone ?>" class="support-link">Call: <?= $settings->contact_phone ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="support">
        <h2>Help with Application</h2>
        <p class="paragraph">
            If you have any issues with the application, please contact our support team.
        </p>
        <a href="mailto:support@gymrat.com" class="support-link">Mail: support@gymrat.com</a>
    </div>
</main>