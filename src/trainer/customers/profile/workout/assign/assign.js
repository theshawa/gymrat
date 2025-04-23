document.addEventListener('DOMContentLoaded', function () {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');

            // Hide all tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Show content related to clicked tab
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Exercise counter for generating unique IDs
    let exerciseCounter = 1;

    // Function to add a new exercise field
    window.addExercise = function () {
        const container = document.getElementById('exercises-container');
        const newExercise = document.createElement('div');
        newExercise.className = 'exercise-item';

        // Create a unique ID for new form elements
        const uniqueId = exerciseCounter++;

        newExercise.innerHTML = `
            <div class="form-row">
                <div class="form-col">
                    <label class="form-label" for="exercise_id_${uniqueId}">Exercise</label>
                    <select name="exercise_id[]" id="exercise_id_${uniqueId}" class="form-select" required>
                        <option value="">Select an exercise</option>
                        ${getExerciseOptions()}
                    </select>
                </div>
                <div class="form-col small">
                    <label class="form-label" for="exercise_day_${uniqueId}">Day</label>
                    <input type="number" name="exercise_day[]" id="exercise_day_${uniqueId}" class="form-input" min="1" max="7" value="1" required>
                </div>
                <button type="button" class="remove-btn" onclick="removeExercise(this)" title="Remove exercise">×</button>
            </div>
            <div class="form-row">
                <div class="form-col small">
                    <label class="form-label" for="exercise_sets_${uniqueId}">Sets</label>
                    <input type="number" name="exercise_sets[]" id="exercise_sets_${uniqueId}" class="form-input" min="1" max="20" value="3" required>
                </div>
                <div class="form-col small">
                    <label class="form-label" for="exercise_reps_${uniqueId}">Reps</label>
                    <input type="number" name="exercise_reps[]" id="exercise_reps_${uniqueId}" class="form-input" min="1" max="100" value="10" required>
                </div>
            </div>
        `;

        container.appendChild(newExercise);

        // Scroll to the newly added exercise
        newExercise.scrollIntoView({ behavior: 'smooth', block: 'end' });
    };

    // Function to remove an exercise field
    window.removeExercise = function (button) {
        const exerciseItem = button.closest('.exercise-item');

        // Check if this is the last exercise item
        const container = document.getElementById('exercises-container');
        if (container.children.length > 1) {
            exerciseItem.remove();
        } else {
            // Alert the user if they're trying to remove the last exercise
            alert('You must have at least one exercise in the workout plan.');
        }
    };

    // Function to get all exercise options from the first select box
    function getExerciseOptions() {
        const firstSelect = document.querySelector('select[name="exercise_id[]"]');
        return firstSelect ? firstSelect.innerHTML : '';
    }

    // Form validation before submission
    document.getElementById('custom-workout-form').addEventListener('submit', function (event) {
        // Validate workout name
        const planName = document.getElementById('plan_name').value.trim();
        if (!planName) {
            event.preventDefault();
            alert('Please enter a workout plan name.');
            document.getElementById('plan_name').focus();
            return;
        }

        // Validate workout description
        const planDescription = document.getElementById('plan_description').value.trim();
        if (!planDescription) {
            event.preventDefault();
            alert('Please enter a workout plan description.');
            document.getElementById('plan_description').focus();
            return;
        }

        // Validate at least one exercise
        const exerciseSelects = document.querySelectorAll('select[name="exercise_id[]"]');
        let hasValidExercise = false;

        exerciseSelects.forEach(select => {
            if (select.value) {
                hasValidExercise = true;
            }
        });

        if (!hasValidExercise) {
            event.preventDefault();
            alert('Please add at least one exercise to the workout plan.');
            return;
        }

        // Additional validation for sets and reps
        const exerciseSets = document.querySelectorAll('input[name="exercise_sets[]"]');
        const exerciseReps = document.querySelectorAll('input[name="exercise_reps[]"]');

        for (let i = 0; i < exerciseSets.length; i++) {
            if (parseInt(exerciseSets[i].value) <= 0) {
                event.preventDefault();
                alert('Sets must be greater than 0.');
                exerciseSets[i].focus();
                return;
            }

            if (parseInt(exerciseReps[i].value) <= 0) {
                event.preventDefault();
                alert('Reps must be greater than 0.');
                exerciseReps[i].focus();
                return;
            }
        }
    });

    // Visual feedback when hovering over plan cards
    const planCards = document.querySelectorAll('.plan-card');
    planCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.classList.add('hover');
        });

        card.addEventListener('mouseleave', function () {
            this.classList.remove('hover');
        });
    });
});