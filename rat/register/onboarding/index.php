<?php
$pageConfig = [
    "title" => "Onboarding",
    "styles" => ["/rat/styles/auth.css", "./onboarding.css"],
    "scripts" => ["/rat/scripts/forms.js"]
];

require_once "../../includes/header.php";

?>

<main class="onboarding">
    <img width="100" src="./animation1.gif" alt="Man lifting a weight">
    <h1>Welcome to GYMRAT!</h1>
    <p class="paragraph">Let us gather some details about you to personalize your fitness journey at GYMRAT. This will help us tailor the best experience for you at our gym.</p>
    <form action="onboarding_process.php" method="post">
        <div class="question">
            <span class="title">What is your gender?</span>
            <div class="gender">
                <label class="input line">
                    <input type="radio" name="gender" value="male" checked required>
                    <span class="option">Male</span>
                </label>
                <label class="input line">
                    <input type="radio" name="gender" value="female" required>
                    <span class="option">Female</span>
                </label>
            </div>
        </div>
        <div class="question">
            <span class="title">How old are you?</span>
            <div class="line">
                <input class="input" min="10" max="150" type="number" name="age" placeholder="16" required>
                <span class="">YRS</span>
            </div>
        </div>
        <div class="question">
            <span class="title">What is your weight?</span>
            <div class="line">
                <input class="input" min="10" type="number" name="weight" placeholder="20" required>
                <span class="">KG</span>
            </div>
        </div>
        <div class="question">
            <span class="title">What is your height?</span>
            <div class="line">
                <input class="input" min="10" type="number" name="height" placeholder="160" required>
                <span class="">CM</span>
            </div>
        </div>
        <div class="question">
            <span class="title">What is your goal?</span>
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
            <span class="title">Your physical activity level?</span>
            <select name="physical_activity_level" class="input">
                <option disabled value="">Select option</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>
        <div class="question">
            <span class="title">Your dietary preferences?</span>
            <select name="dietary_preferences" class="input">
                <option disabled value="">Select option</option>
                <option value="vegitarian">Vegitarian</option>
                <option value="non_vegitarian">Non-vegitarian</option>
                <option value="gluten_free">Gluten - Free</option>
                <option value="keto">Keto</option>
                <option value="paleo">Paleo</option>
                <option value="no_preferences">No Preferences</option>
            </select>
        </div>
        <div class="question">
            <span class="title">Your allergies?</span>
            <textarea name="allergies" class="input" placeholder="Describe your allergies briefly"></textarea>
        </div>
        <button class="btn">Let's get started</button>
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
<?php require_once "../../includes/footer.php" ?>