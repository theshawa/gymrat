<?php
// File path: src/trainer/models/WorkoutModel.php

require_once __DIR__ . "/../../db/Model.php";

class WorkoutModel extends Model
{
    protected $table = "customer_workouts";

    /**
     * Get a customer's assigned workout
     * 
     * @param int $customerId The customer ID
     * @return array|null The workout data or null if not found
     */
    public function getCustomerWorkout($customerId)
    {
        // In a fully implemented system, this would fetch from the database
        // For now, we'll return mock data that matches the structure we expect

        // Mock workout data structure that would come from the database
        $mockWorkouts = [
            1 => [
                'id' => 101,
                'name' => 'Beginner Full Body',
                'exercises' => [
                    ['name' => 'Squats', 'sets' => 3, 'reps' => 10],
                    ['name' => 'Push-ups', 'sets' => 3, 'reps' => 8],
                    ['name' => 'Lunges', 'sets' => 3, 'reps' => 10],
                    ['name' => 'Plank', 'sets' => 3, 'reps' => 30] // seconds
                ]
            ],
            2 => [
                'id' => 102,
                'name' => 'Upper Body Focus',
                'exercises' => [
                    ['name' => 'Bench Press', 'sets' => 4, 'reps' => 8],
                    ['name' => 'Pull-ups', 'sets' => 3, 'reps' => 6],
                    ['name' => 'Shoulder Press', 'sets' => 3, 'reps' => 10],
                    ['name' => 'Tricep Dips', 'sets' => 3, 'reps' => 12]
                ]
            ],
            // Default workout for any customer ID not in our mock data
            'default' => [
                'id' => 123,
                'name' => 'Full Body Strength',
                'exercises' => [
                    ['name' => 'Power Squats', 'sets' => 3, 'reps' => 4],
                    ['name' => 'Weight Lifting', 'sets' => 6, 'reps' => 4],
                    ['name' => 'Belly Push', 'sets' => 12, 'reps' => 4],
                    ['name' => 'Arm Swing', 'sets' => 8, 'reps' => 4]
                ]
            ]
        ];

        // Return the workout for this customer or the default
        return isset($mockWorkouts[$customerId])
            ? $mockWorkouts[$customerId]
            : $mockWorkouts['default'];
    }

    /**
     * Request to edit a customer's workout
     * 
     * @param int $trainerId The trainer ID making the request
     * @param int $customerId The customer whose workout will be edited
     * @return bool True if request was successfully created
     */
    public function requestWorkoutEdit($trainerId, $customerId)
    {
        // In a real system, this would create a record in a database table
        // For now, we'll just return true to simulate success
        return true;
    }

    /**
     * Get all available workout templates that can be assigned to customers
     * 
     * @return array List of workout templates
     */
    public function getWorkoutTemplates()
    {
        // This would fetch from a workout_templates table in the real system
        return [
            ['id' => 1, 'name' => 'Beginner Full Body'],
            ['id' => 2, 'name' => 'Upper Body Focus'],
            ['id' => 3, 'name' => 'Lower Body Focus'],
            ['id' => 4, 'name' => 'Core Strength'],
            ['id' => 5, 'name' => 'Cardio Blast']
        ];
    }
}