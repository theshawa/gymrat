<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;


require_once "../../../db/models/CustomerInitialData.php";
$initial_data = new CustomerInitialData();
try {
    $initial_data->get_by_id($_SESSION['auth']['id']);
} catch (PDOException $e) {
    die("Failed to get initial data due to error: " . $e->getMessage());
}

if (!$initial_data->customer_id) {
    die("Initial data not found for customer ID: " . $_SESSION['auth']['id']);
}

$pageConfig = [
    "title" => "Update Initial Data",
    "styles" => ["/rat/styles/auth.css", "../../onboarding/onboarding.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 3
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main class="onboarding">
    <form action="update_initial_data_process.php" method="post" style="margin-top: 0;">
        <p class="paragraph small">
            *We don't recommend you to change this information as it was collected initially at the start of your fitness journey with the app.
        </p>
        <div class="question">
            <span class="title">Gender</span>
            <div class="gender">
                <label class="input line radio">
                    <input type="radio" name="gender" value="male" <?= $initial_data->gender == "male" ? "checked" : "" ?> required>
                    <div class="tick">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="option">Male</span>
                </label>
                <label class="input line radio">
                    <input type="radio" name="gender" value="female" <?= $initial_data->gender == "female" ? "checked" : "" ?> required>
                    <div class="tick">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="option">Female</span>
                </label>
            </div>
        </div>
        <div class="question">
            <span class="title">Age</span>
            <div class="line">
                <input class="input" min="10" max="150" type="number" name="age" placeholder="16" value="<?= $initial_data->age ?>" required>
                <span class="">YRS</span>
            </div>
        </div>
        <div class="question">
            <span class="title">Goal</span>
            <select name="goal" class="input">
                <option disabled value="">Select option</option>
                <?php
                $goals = [
                    ['value' => 'weight_loss', 'label' => 'Weight Loss'],
                    ['value' => 'weight_gain', 'label' => 'Weight Gain'],
                    ['value' => 'muscle_mass_gain', 'label' => 'Muscle Mass Gain'],
                    ['value' => 'shape_body', 'label' => 'Shape Body'],
                    ['value' => 'other', 'label' => 'Other']
                ];
                foreach ($goals as $goal) {
                    $selected = $initial_data->goal == $goal['value'] ? 'selected' : '';
                    echo "<option value=\"{$goal['value']}\" $selected>{$goal['label']}</option>";
                }
                ?>
            </select>
            <textarea name="other_goal" class="input" placeholder="Describe your goal briefly"><?= $initial_data->goal == "other" ? $initial_data->other_goal : "" ?></textarea>
        </div>
        <div class="question">
            <span class="title">Physical activity level</span>
            <select name="physical_activity_level" class="input">
                <?php
                $activity_levels = [
                    ['value' => 'beginner', 'label' => 'Beginner'],
                    ['value' => 'intermediate', 'label' => 'Intermediate'],
                    ['value' => 'advanced', 'label' => 'Advanced'],
                ];
                foreach ($activity_levels as $level) {
                    $selected = $initial_data->physical_activity_level == $level['value'] ? 'selected' : '';
                    echo "<option value=\"{$level['value']}\" $selected>{$level['label']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="question">
            <span class="title">Dietary preference</span>
            <select name="dietary_preference" class="input">
                <?php
                $dietary_preferences = [
                    ['value' => 'vegitarian', 'label' => 'Vegitarian'],
                    ['value' => 'non_vegitarian', 'label' => 'Non-vegitarian'],
                    ['value' => 'gluten_free', 'label' => 'Gluten - Free'],
                    ['value' => 'keto', 'label' => 'Keto'],
                    ['value' => 'paleo', 'label' => 'Paleo'],
                    ['value' => '', 'label' => 'No Preferences']
                ];
                foreach ($dietary_preferences as $preference) {
                    $selected = $initial_data->dietary_preference == $preference['value'] ? 'selected' : '';
                    echo "<option value=\"{$preference['value']}\" $selected>{$preference['label']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="question">
            <span class="title">Allergies</span>
            <textarea name="allergies" class="input" placeholder="Describe your allergies briefly(if there's any)"><?= $initial_data->allergies ?></textarea>
        </div>
        <input type="hidden" name="weight" value="<?= $initial_data->weight ?>">
        <input type="hidden" name="height" value="<?= $initial_data->height ?>">
        <button class="btn">Save</button>
    </form>
</main>
<script>
    const goal = document.querySelector('select[name="goal"]');
    const otherGoal = document.querySelector('textarea[name="other_goal"]');

    otherGoal.style.display = 'none';
    otherGoal.removeAttribute('required');

    const handleOther = () => {
        if (goal.value === 'other') {
            otherGoal.style.display = 'block';
            otherGoal.setAttribute('required', 'true');
        } else {
            otherGoal.style.display = 'none';
            otherGoal.removeAttribute('required');
        }
    }

    goal.addEventListener('change', handleOther);
    handleOther();
</script>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>