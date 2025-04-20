<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;


require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Trainer.php";
require_once "../../../../db/models/Customer.php";
require_once "../../../../db/models/TrainerRating.php";


$sidebarActive = 4;
$pageStyles = ["../../admin.css"];


$trainer = new Trainer();
try {
    $trainer->id = $id;
    $trainer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch trainer: " . $e->getMessage(), "/staff/admin");
    exit;
}
$_SESSION['trainer'] = serialize($trainer);


$trainer_ratings = null;
$trainerRatingModel = new TrainerRating();
try {
    $trainer_ratings = $trainerRatingModel->get_all_of_trainer($trainer->id);
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch trainer ratings: " . $e->getMessage();
}


$customers_assigned = null;
$customerModel = new Customer();
try {
    $customers_assigned = $customerModel->count_customers_by_trainer($trainer->id);
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch customers assigned: " . $e->getMessage();
}


// Do necessary calculations on trianer ratings data
$rating_counts = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
];
$total_rating = 0;
$totalReviews = count($trainer_ratings);
foreach ($trainer_ratings as $rating) {
    $stars = $rating->rating;
    if (isset($rating_counts[$stars])) {
        $rating_counts[$stars]++;
    } else {
        // unexpected rating value
        $rating_counts[$stars] = 1;
    }
    $total_rating += $stars;
}
$average_rating = $totalReviews > 0 ? $total_rating / $totalReviews : 0;


$menuBarConfig = [
    "title" => $trainer->fname . " " . $trainer->lname,
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers/index.php",
];


require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

            <div class="trainer-view-profile">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                <?php if (!empty($trainer->avatar)): ?>
                    <img src="../../../../uploads/<?= $trainer->avatar ?>" alt="Trainer Avatar"  class="trainer-view-avatar">
                <?php else: ?>
                    <img src="../../../../uploads/default-images/infoCardDefault.png" alt="Default Avatar" class="trainer-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $trainer->fname . " " . $trainer->lname ?></h1>
                    <h1 style="margin: 10px;"><?= $trainer->username ?></h1>
                    <h1 style="margin: 10px;"><?= $trainer->phone ?></h1>
                </div>
                <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                    <a href="/staff/admin/trainers/profile/index.php?id=<?= $trainer->id ?>" style="margin: 10px 0px; width: 150px; height: 40px;" 
                    class="staff-button secondary">Edit Profile</a>
                </div>
            </div>

            <div class="trainer-view-menu">
                <div style="display: grid; grid-template-rows: 2fr 1fr;">
                    <div style="grid-row: 1; align-self: start; justify-self: end; text-align: right;">
                        <h1 style="margin: 10px;">Trainer Bio</h1>
                        <p style="margin: 10px;"><?= $trainer->bio ?></p>
                    </div>
                    <div style="grid-row: 2; align-self: end; justify-self: end; text-align: right;
                    display: grid; grid-template-rows: 1fr ; grid-template-columns: 1fr 1fr; width: 100%;">
                        <div style="grid-row: 1; grid-column: 2; align-self: end; justify-self: end; text-align: right; width: 100%; height: 100%;">
                            <h1 style="margin: 2px 10px; font-size: 26px;"> 
                                <?= ($customers_assigned === 1) ? 
                                    $customers_assigned . " Rat" :
                                    (($customers_assigned) ? $customers_assigned . " Rats" : "0 Rats")
                                ?>
                            </h1>
                            <p style="margin: 0 10px;">currently assigned to</p>
                        </div>
                        <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: end; text-align: left; width: 100%; height: 100%;">
                            <a href="/staff/admin/trainers/assignments/index.php?id=<?= $trainer->id ?>" style="margin: 10px 0px; width: 200px; height: 40px;" 
                                class="staff-button secondary">
                                Change Assigned Rats  
                            </a>
                        </div>
                    </div>
                </div>

                <div class="trainer-view-ratings">
                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 style="margin: 2px 0; font-size: 28px;"><?= number_format($average_rating, 1) ?> / 5</h1>
                        <p>Average Rating</p>
                    </div>
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                        <?php if ($rating_counts[5] > 0): ?>
                            <p style="font-family: Courier;"><?= $rating_counts[5] ?>&emsp;★ ★ ★ ★ ★ </p>
                        <?php endif; ?>
                        <?php if ($rating_counts[4] > 0): ?>
                            <p style="font-family: Courier;"><?= $rating_counts[4] ?>&emsp;★ ★ ★ ★  </p>
                        <?php endif; ?>
                        <?php if ($rating_counts[3] > 0): ?>
                            <p style="font-family: Courier;"><?= $rating_counts[3] ?>&emsp;★ ★ ★  </p>
                        <?php endif; ?>
                        <?php if ($rating_counts[2] > 0): ?>
                            <p style="font-family: Courier;"><?= $rating_counts[2] ?>&emsp;★ ★ </p>
                        <?php endif; ?>
                        <?php if ($rating_counts[1] > 0): ?>
                            <p style="font-family: Courier;"><?= $rating_counts[1] ?>&emsp;★ </p>
                        <?php endif; ?>
                    </div>
                    <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left;">
                        <a href="/staff/admin/trainers/ratings/index.php?id=<?= $trainer->id ?>" style="margin: 10px 0px; width: 130px; height: 40px;" 
                            class="staff-button primary">
                            View Ratings  
                        </a>
                    </div>
                </div>

            </div>

        </div>
        <!-- <pre> <?= print_r($trainer_ratings) ?> </pre> -->
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>