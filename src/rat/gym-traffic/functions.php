<?php

require_once __DIR__ . "/../../db/models/Settings.php";
require_once __DIR__ . "/../../db/models/WorkoutSession.php";

function get_traffic()
{
    $settings = new Settings();
    try {
        $settings->get_all();
    } catch (\Throwable $th) {
        die("Failed to get settings: " . $th->getMessage());
    }

    $session_model = new WorkoutSession();
    $session_model->fill([
        'user' => $_SESSION['auth']['id'],
    ]);

    $sessions = [];
    try {
        $sessions = $session_model->get_all_live();
    } catch (\Throwable $th) {
        die("Failed to get workout sessions: " . $th->getMessage());
    }

    $active_sessions = array_filter($sessions, function ($session) use ($settings) {
        return $session->get_duration_in_hours() < $settings->workout_session_expiry;
    });

    $active_sessions_count = count($active_sessions);
    $max_sessions = !$settings->max_capacity ? 50 : $settings->max_capacity;
    $traffic = $active_sessions_count / $max_sessions;
    $rat_count_text = "";
    if ($active_sessions_count === 0) {
        $rat_count_text = "No rats are working out";
    } else if ($active_sessions_count < 5) {
        $rat_count_text = "Less than 5 rats are working out";
    } elseif ($active_sessions_count < 10) {
        $rat_count_text = "Less than 10 rats are working out";
    } else if ($active_sessions_count > $max_sessions) {
        $rat_count_text = "More than $max_sessions rats are working out";
    } else {
        $traffic_range_min = 0;
        $traffic_range_max = 0;
        for ($i = 10; $i <= $max_sessions; $i += 10) {
            $traffic_range_min = $i;
            $traffic_range_max = $i + 10;
            if ($active_sessions_count >= $traffic_range_min && $active_sessions_count < $traffic_range_max) {
                break;
            }
        }
        $rat_count_text = "$traffic_range_min - $traffic_range_max rats are working out";
    }

    $status = "good";
    $status_text = "Gym is all yours";
    if ($traffic > 0.66) {
        $status = "bad";
        $status_text = "Gym is packed";
    } else if ($traffic > 0.33) {
        $status = "average";
        $status_text = "Gym is buzzing";
    } else {
        $status = "good";
        $status_text = "Gym is all yours";
    }

    return [
        'value' => $traffic,
        'rat_count_text' => $rat_count_text,
        'status' => $status,
        'status_text' => $status_text,
    ];
}
