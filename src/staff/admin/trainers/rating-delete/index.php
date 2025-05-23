<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
$rating_id = $_GET['rating'] ?? null;

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Trainer.php";
require_once "../../../../db/models/TrainerRating.php";
require_once "../../../../db/models/Customer.php";


$sidebarActive = 4;
$pageStyles = ["../../admin.css"];

$trainer = new Trainer();
if (!isset($_SESSION['trainer'])){    
    try {
        $trainer->id = $id;
        $trainer->get_by_id();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch trainer: " . $e->getMessage(), "/staff/admin/trainers/view/index.php?id=$id");
        exit;
    }
    $_SESSION['trainer'] = serialize($trainer);
} else {
    $trainer = unserialize($_SESSION['trainer']);
}


$trainer_ratings = null;
$trainerRatingModel = new TrainerRating();
try {
    $trainer_ratings = $trainerRatingModel->get_by_id($rating_id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch ratings: " . $e->getMessage(), "/staff/admin/trainers/view/index.php?id=$id");
    exit;
}

$customers = null;
$customerModel = new Customer();
try {
    // Restructure $customers into an associative array with 'id' as the key
    $customers = array_reduce($customerModel->get_all(), function ($result, $customer) {
        $result[$customer->id] = $customer;
        return $result;
    }, []);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin/trainers/view/index.php?id=$id");
    exit;
}



$menuBarConfig = [
    "title" => $trainer->fname . " " . $trainer->lname . " Ratings",
    "showBack" => true,
    "goBackTo" => "/staff/admin/trainers/ratings/index.php?id=" . $trainer->id,
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

            <div class="trainer-view-profile alt">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                <?php if (!empty($trainer->avatar)): ?>
                    <img src="../../../../uploads/<?= $trainer->avatar ?>" alt="Trainer Avatar"  class="trainer-view-avatar">
                <?php else: ?>
                    <img src="../../../../uploads/default-images/default-avatar.png" alt="Default Avatar" class="trainer-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: center; text-align: center;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $trainer->fname . " " . $trainer->lname ?></h1>
                </div>
            </div>

            <div style="grid-column: 2; align-self: start; justify-self: end; text-align: right; width:100%;">
                <h1 style="margin-bottom: 20px;">Trainer Rating No <?= $rating_id ?></h1>
                <div class="trainer-assignment-list-item">
                    <div style="grid-column: 1; justify-self: start; align-self: center; text-align: left;">
                        <h1><?= $trainer_ratings->rating ?> Star</h1>
                        <p><?= $trainer_ratings->review ?></p>
                    </div>
                    <div style="grid-column: 2; justify-self: end; align-self: end;">
                        <div style=" text-align: right;">
                            <p><?= $customers[$trainer_ratings->customer_id]->fname . " " . $customers[$trainer_ratings->customer_id]->lname ?></p>
                            <p><?= $trainer_ratings->created_at->format('Y-m-d') ?></p>
                        </div>
                    </div>
                </div>
                <form action="delete_rating.php" method="POST">
                    <input type="hidden" name="rating_id" value="<?= $rating_id ?>">
                    <input type="hidden" name="trainer_id" value="<?= $id ?>">
                    <div class="staff-record-delete-div">
                        <h2>Are you sure you want to delete this rating?</h2>
                        <p>This action cannot be undone.</p>
                        <button type="submit">Delete</button>
                    </div>
                </form>
            </div>
        
            <!-- <pre> <?= print_r($customers) ?></pre> -->
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>