<?php

$pageConfig = [
    "title" => "Membership Plan Count",
    "styles" => [],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "../../db/models/MembershipPlan.php";
$plan_model = new MembershipPlan();
try {
    $data = $plan_model->get_all_titles_with_user_counts();
} catch (\Throwable $th) {
    die("Failed to fetch plans!");
}

?>

<style>
    .entity-list {
        display: flex;
        flex-direction: column;
        margin-top: 20px;
    }

    .entity {
        padding: 12px 16px;
        border: 1px solid var(--color-zinc-800);
        border-radius: 16px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
</style>

<main>
    <h2>Membership Vise Customer Count View</h2>
    <div class="entity-list">
        <?php foreach ($data  as $row): ?>
            <div class="entity">
                <h3><?= $row['title'] ?></h3>
                <p><?= $row['user_count'] ?> customer<?= $row['user_count'] === 1 ? '' : 's' ?> subscribed to this plan</p>
            </div>
        <?php endforeach; ?>
    </div>

</main>