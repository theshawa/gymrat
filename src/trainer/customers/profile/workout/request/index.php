<?php
// File: src/trainer/workouts/request/index.php
require_once('../../../../../auth-guards.php');
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

$pageConfig = [
    "title" => "Request Workout",
    "navbar_active" => 1,
    "titlebar" => [
        "back_url" => "../",
        "title" => "REQUEST WORKOUT"
    ]
];

require_once('../../../../includes/header.php');
require_once "../../../../includes/titlebar.php";
?>

<main>
    <form class="form" action="request_process.php" method="post">
        <div class="form-field">
            <label for="description">Workout Details</label>
            <textarea id="description" name="description" class="input" rows="6" required
                placeholder="Describe the workout plan you need (client fitness level, goals, equipment access, etc.)"></textarea>
        </div>
        <div class="form-field">
            <label for="workout_type">Workout Type</label>
            <select id="workout_type" name="workout_type" class="input" required>
                <option value="">Select Type</option>
                <option value="strength">Strength Training</option>
                <option value="cardio">Cardio</option>
                <option value="hiit">HIIT</option>
                <option value="flexibility">Flexibility/Mobility</option>
                <option value="sport">Sport Specific</option>
                <option value="custom">Custom Program</option>
            </select>
        </div>
        <div class="form-field">
            <label for="duration">Program Duration (days)</label>
            <input type="number" id="duration" name="duration" class="input" min="1" max="365" value="30">
        </div>
        <button type="submit" class="btn">Submit Request</button>
    </form>
</main>

<style>
    .form-field {
        margin-bottom: 20px;
    }

    .form-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #A1A1AA;
    }

    .form {
        padding: 10px;
    }
</style>

<?php require_once "../../../../includes/navbar.php" ?>
<?php require_once "../../../../includes/footer.php" ?>