<?php
$pageConfig = [
    "title" => "Update Initial Data",
    "styles" => ["/rat/styles/auth.css", "../../onboarding/onboarding.css"],
    "scripts" => ["/rat/scripts/forms.js"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 3,
    "need_auth" => true
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
                    <input type="radio" name="gender" value="male" checked required>
                    <div class="tick">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="option">Male</span>
                </label>
                <label class="input line radio">
                    <input type="radio" name="gender" value="female" required>
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
                <input class="input" min="10" max="150" type="number" name="age" placeholder="16" required>
                <span class="">YRS</span>
            </div>
        </div>
        <div class="question">
            <span class="title">Goal</span>
            <select name="goal" class="input">
                <option disabled value="">Select option</option>
                <option value="weight_loss">Weight Loss</option>
                <option value="weight_gain">Weight Gain</option>
                <option value="muscle_mass_gain">Muscle Mass Gain</option>
                <option value="shape_body">Shape Body</option>
                <option value="other">Other</option>
            </select>
            <textarea name="other_goal" class="input" placeholder="Describe your goal briefly"></textarea>
        </div>
        <div class="question">
            <span class="title">Physical activity level</span>
            <select name="physical_activity_level" class="input">
                <option disabled value="">Select option</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>
        <div class="question">
            <span class="title">Dietary preferences</span>
            <select name="dietary_preferences" class="input">
                <option value="vegitarian">Vegitarian</option>
                <option value="non_vegitarian">Non-vegitarian</option>
                <option value="gluten_free">Gluten - Free</option>
                <option value="keto">Keto</option>
                <option value="paleo">Paleo</option>
                <option value="" selected>No Preferences</option>
            </select>
        </div>
        <div class="question">
            <span class="title">Allergies</span>
            <textarea name="allergies" class="input" placeholder="Describe your allergies briefly(if there's any)"></textarea>
        </div>
        <button class="btn">Save</button>
    </form>
</main>
<script>
    const goal = document.querySelector('select[name="goal"]');
    const otherGoal = document.querySelector('textarea[name="other_goal"]');

    otherGoal.style.display = 'none';
    otherGoal.removeAttribute('required');

    goal.addEventListener('change', (e) => {
        if (e.target.value === 'other') {
            otherGoal.style.display = 'block';
            otherGoal.setAttribute('required', 'true');
        } else {
            otherGoal.style.display = 'none';
            otherGoal.removeAttribute('required');
        }
    });
</script>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>