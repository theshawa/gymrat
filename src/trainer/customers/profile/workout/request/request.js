// File: src/trainer/customers/profile/workout/request/request.js
document.addEventListener('DOMContentLoaded', function () {
    // Exercise counter for generating unique IDs
    let exerciseCounter = 2;

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
                    <label for="exercise_id_${uniqueId}">Exercise</label>
                    <select name="exercise_id[]" id="exercise_id_${uniqueId}" class="form-select">
                        <option value="">Select an exercise</option>
                        ${getExerciseOptions()}
                    </select>
                </div>
                <div class="form-col small">
                    <label for="exercise_day_${uniqueId}">Day</label>
                    <input type="number" name="exercise_day[]" id="exercise_day_${uniqueId}" class="form-input" min="1" max="7" value="1">
                </div>
                <button type="button" class="remove-btn" onclick="removeExercise(this)" title="Remove exercise">×</button>
            </div>
            <div class="form-row">
                <div class="form-col small">
                    <label for="exercise_sets_${uniqueId}">Sets</label>
                    <input type="number" name="exercise_sets[]" id="exercise_sets_${uniqueId}" class="form-input" min="1" max="20" value="3">
                </div>
                <div class="form-col small">
                    <label for="exercise_reps_${uniqueId}">Reps</label>
                    <input type="number" name="exercise_reps[]" id="exercise_reps_${uniqueId}" class="form-input" min="1" max="100" value="10">
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
        const container = document.getElementById('exercises-container');

        // Don't remove if it's the last exercise
        if (container.children.length > 1) {
            exerciseItem.remove();
        } else {
            // Alert the user if they're trying to remove the last exercise
            alert('You must have at least one exercise in the workout plan.');
        }
    };

    // Function to get exercise options, excluding already selected ones
    function getExerciseOptions() {
        const firstSelect = document.querySelector('select[name="exercise_id[]"]');
        if (!firstSelect) return '';

        // Get all existing dropdown selects
        const allSelects = document.querySelectorAll('select[name="exercise_id[]"]');
        
        // Get array of already selected exercise IDs
        const selectedIds = Array.from(allSelects)
            .map(select => select.value)
            .filter(value => value !== ''); // Filter out empty selections
        
        // Clone the options from the first select
        const options = Array.from(firstSelect.options);
        
        // Create HTML for options, excluding already selected ones
        return options.map(option => {
            // Always include the empty "Select an exercise" option
            if (option.value === '') {
                return option.outerHTML;
            }
            
            // Skip this option if it's already selected in any dropdown
            if (selectedIds.includes(option.value)) {
                return '';
            }
            
            return option.outerHTML;
        }).join('');
    }

    // Form validation before submission
    document.querySelector('.form').addEventListener('submit', function (event) {
        // Validate name
        const name = document.getElementById('workout_name').value.trim();
        if (!name) {
            event.preventDefault();
            alert('Please provide a name for the workout plan.');
            document.getElementById('workout_name').focus();
            return;
        }

        // Validate description
        const description = document.getElementById('description').value.trim();
        if (!description) {
            event.preventDefault();
            alert('Please provide a description for the workout plan.');
            document.getElementById('description').focus();
            return;
        }

        // Validate workout type
        const type = document.getElementById('workout_type').value;
        if (!type) {
            event.preventDefault();
            alert('Please select a workout type.');
            document.getElementById('workout_type').focus();
            return;
        }

        // Validate duration
        const duration = parseInt(document.getElementById('duration').value);
        if (isNaN(duration) || duration < 1 || duration > 365) {
            event.preventDefault();
            alert('Duration must be between 1 and 365 days.');
            document.getElementById('duration').focus();
            return;
        }

        // Check if at least one exercise has been selected
        const exerciseSelects = document.querySelectorAll('select[name="exercise_id[]"]');
        let selectedExercises = 0;

        exerciseSelects.forEach(select => {
            if (select.value) {
                selectedExercises++;
            }
        });

        if (selectedExercises === 0) {
            event.preventDefault();
            alert('Please select at least one exercise for the workout plan.');
            return;
        }
    });
});