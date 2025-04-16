<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$pageConfig = [
    "title" => "My Profile",
    "navbar_active" => 3,
    "styles" => ["./profile.css"]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

require_once "../../db/models/Customer.php";
require_once "../../db/models/MembershipPlan.php";

$user = new Customer();
$user->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    die("Failed to get user due to error: " . $e->getMessage());
}

$plan = new MembershipPlan();
$plan->fill([
    "id" => $user->membership_plan
]);

try {
    $plan->get_by_id();
} catch (PDOException $e) {
    die("Failed to get plan due to error: " . $e->getMessage());
}

$plan_expiry = null;
if ($user->membership_plan_activated_at) {
    $plan_expiry_date = $user->membership_plan_activated_at->add(new DateInterval("P" . $plan->duration . "D"));
    $now = new DateTime();
    $diff = $plan_expiry_date->diff($now);
    $plan_expiry = $diff->days;
}


$avatar = $user->avatar ? "/uploads/" . $user->avatar : "/uploads/default-images/default-avatar.png";

?>

<main>
    <img src="<?= $avatar ?>" alt="" class="avatar">
    <h1><?= $user->fname ?> <?= $user->lname ?></h1>
    <div class="lines">
        <div class="line">
            <span class="title">Email</span>
            <a href="mailto:<?= $user->email ?>" class="content"><?= $user->email ?></a>
        </div>
        <div class="line">
            <span class="title">Phone</span>
            <a href="tel:<?= $user->phone ?>" class="content"><?= $user->phone ?></a>
        </div>
        <div class="line">
            <span class="title">Joined at</span>
            <p class="content"><?= $user->created_at->format("M d, Y") ?></p>
        </div>
        <?php if ($user->updated_at->format("M d, Y, h:i A") !== $user->created_at->format("M d, Y, h:i A")): ?>
            <div class="line">
                <span class="title">Last updated at</span>
                <p class="content"><?= $user->updated_at->format("M d, Y, h:i A") ?></p>
            </div>
        <?php endif; ?>
        <div class="line">
            <span class="title">Current Subscription Plan</span>
            <p class="content">
                <?= $plan->name ?>
                <?php if ($plan_expiry): ?>
                    <span class="paragraph small">(<?= $plan_expiry ?> day<?= $plan_expiry == 1 ? "" : "s" ?>
                        remaining)</span>
                <?php endif; ?>
            </p>
        </div>

    </div>
    <a href="./update" class="btn">Update Profile</a>
    <button onclick="logout()" class="btn secondary">Logout</button>
    <script>
        const logout = () => {
            if (<?= isset($_SESSION['workout_session']) ? 'true' : 'false' ?>) {
                alert("You have an active workout session. Please end it before logging out.");
            } else {
                window.location.href = "../logout.php";
            }
        }
    </script>
    <?php if (!$user->avatar): ?>
        <p class="paragraph" style="text-align: center;font-size: 10px;margin-top:20px;color: var(--color-zinc-500)">Default
            avatar image is <br />downloaded from <a href="https://www.freepik.com" target="_blank"
                referrerpolicy="no-reffer" style="text-decoration: underline;">www.freepik.com</a>.</p>
    <?php endif; ?>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>