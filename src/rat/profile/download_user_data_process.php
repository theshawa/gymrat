<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

require_once "../../generate-pdf.php";

require_once "../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $user->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

require_once "../../db/models/CustomerInitialData.php";
$initialData = new CustomerInitialData();
try {
    $initialData->get_by_id($_SESSION['auth']['id']);
} catch (\Throwable $th) {
    die("Failed to get initial data: " . $th->getMessage());
}

require_once "../../db/models/BmiRecord.php";
$bmi_records = [];
try {
    $bmi_records = (new BmiRecord())->get_all_of_user($_SESSION['auth']['id']);
} catch (\Throwable $th) {
    die("Failed to get BMI data: " . $th->getMessage());
}

require_once "../../db/models/Complaint.php";
$complaints = [];
try {
    $complaints = (new Complaint())->get_all_of_user($_SESSION['auth']['id'], "rat");
} catch (\Throwable $th) {
    die("Failed to get complaints: " . $th->getMessage());
}

require_once "../../db/models/MembershipPayment.php";
$membership_payments = [];
try {
    $membership_payments = (new MembershipPayment())->get_all_of_user($_SESSION['auth']['id']);
} catch (\Throwable $th) {
    die("Failed to get membership payments: " . $th->getMessage());
}

require_once "../../db/models/TrainerRating.php";
$trainer_ratings = [];
try {
    $trainer_ratings = (new TrainerRating())->get_all_of_user($_SESSION['auth']['id']);
} catch (\Throwable $th) {
    die("Failed to get trainer ratings: " . $th->getMessage());
}

require_once "../../db/models/WorkoutSession.php";
$workout_sessions = [];
$workoutSessionModel = new WorkoutSession();
$workoutSessionModel->fill([
    'user' => $_SESSION['auth']['id']
]);
try {
    $workout_sessions = $workoutSessionModel->get_all_by_user();
} catch (\Throwable $th) {
    die("Failed to get workout sessions: " . $th->getMessage());
}

require_once "../../utils.php";

$user_data = [
    'ID' => $user->id,
    'First Name' => $user->fname,
    'Last Name' => $user->lname,
    'Email' => $user->email,
    'Phone' => $user->phone,
    'Password Hash' => $user->password,
    'Created At' => format_time($user->created_at),
    'Updated At' => format_time($user->updated_at),
];

$user_html = "";
foreach ($user_data as $key => $value) {
    $user_html .= "<tr><td width='30%' class='grayed'>$key: </td><td width='70%'>$value</td></tr>";
}

$initial_data = [
    'Gender' => $initialData->gender,
    'Age' => $initialData->age,
    'Weight' => $initialData->weight,
    'Height' => $initialData->height,
    'Goal' => $initialData->goal === "other" ? $initialData->other_goal : $initialData->goal,
    'Physical Activity Level' => $initialData->physical_activity_level,
    'Dietary Preference' => $initialData->dietary_preference,
    'Allergies' => $initialData->allergies,
];

$initial_data_html = "";
foreach ($initial_data as $key => $value) {
    $initial_data_html .= "<tr><td width='30%' class='grayed'>$key: </td><td width='70%'>$value</td></tr>";
}

$complaints_html = "";
foreach ($complaints as $complaint) {
    $created_at = format_time($complaint->created_at);
    $complaints_html .= "<tr><td width='20%'>$complaint->type</td><td width='50%'>$complaint->description</td><td>$created_at</td></tr>";
}

$complaints_html = empty($complaints_html) ? "<tr><td colspan='3' class='no-data'>No complaints yet.</td></tr>" : $complaints_html;

$trainer_ratings_html = "";
foreach ($trainer_ratings as $trainer_rating) {
    $created_at = format_time($trainer_rating->created_at);
    $trainer_ratings_html .= "<tr><td width='20%'>$trainer_rating->rating/5</td><td width='50%'>$trainer_rating->review</td><td>$created_at</td></tr>";
}

$trainer_ratings_html = empty($trainer_ratings_html) ? "<tr><td colspan='3' class='no-data'>No ratings yet.</td></tr>" : $trainer_ratings_html;

$membership_payments_html = "";
require_once "../../db/models/MembershipPlan.php";
try {
    $membership_plans = (new MembershipPlan())->get_all();
} catch (\Throwable $th) {
    die("Failed to get membership plans: " . $th->getMessage());
}

foreach ($membership_payments as $membership_payment) {
    $plan_name = array_find($membership_plans, function ($plan) use ($membership_payment) {
        return $plan->id === $membership_payment->membership_plan;
    })->name ?? "Unknown";
    $created_at = format_time($membership_payment->completed_at);
    $membership_payments_html .= "<tr><td width='20%'>$membership_payment->amount</td><td width='50%'>$plan_name</td><td>$created_at</td></tr>";
}

$membership_payments_html = empty($membership_payments_html) ? "<tr><td colspan='3' class='no-data'>No membership payments yet.</td></tr>" : $membership_payments_html;

$bmi_records_html = "";
foreach ($bmi_records as $bmi_record) {
    $created_at = format_time($bmi_record->created_at);
    $get_status = function ($bmi) {
        if ($bmi < 18.5) return "Underweight";
        if ($bmi < 24.9) return "Normal weight";
        if ($bmi < 29.9) return "Overweight";
        return "Obesity";
    };
    $status = $get_status($bmi_record->bmi);

    $bmi_records_html .= "<tr><td width='30%'>$bmi_record->bmi ($status)</td><td width='10%'>$bmi_record->weight kg</td><td width='10%'>$bmi_record->height m</td><td width='20%'>$bmi_record->age years</td><td width='30%'>$created_at</td></tr>";
}

$bmi_records_html = empty($bmi_records_html) ? "<tr><td colspan='5' class='no-data'>No BMI records yet.</td></tr>" : $bmi_records_html;

$workout_sessions_html = "";
require_once "../../db/models/Workout.php";
try {
    $workouts = (new Workout())->get_all();
} catch (\Throwable $th) {
    die("Failed to get workouts: " . $th->getMessage());
}
$workout_sessions = array_filter($workout_sessions, function ($session) {
    return $session->ended_at !== null;
});
foreach ($workout_sessions as $workout_session) {
    $workout_name = array_find($workouts, function ($workout) use ($workout_session) {
        return $workout->id === $workout_session->workout;
    })->name ?? "Unknown";
    $started_at = format_time($workout_session->started_at);
    $ended_at = format_time($workout_session->ended_at);
    $workout_sessions_html .= "<tr><td width='30%'>$workout_name</td><td width='10%'>$workout_session->day</td><td width='30%'>$started_at</td><td width='30%'>$ended_at</td></tr>";
}

$workout_sessions_html = empty($workout_sessions_html) ? "<tr><td colspan='4' class='no-data'>No workout sessions yet.</td></tr>" : $workout_sessions_html;

$generated_at = format_time(new DateTime());
$htmlBody = <<<HTML
<p>User data report of $user->fname $user->lname($user->id) generated at $generated_at.</p>
<br/>
<h3 class="panelled">User Data</h3>
<table>
    <tbody>
            $user_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">Initial Data</h3>
<table>
    <tbody>
            $initial_data_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">Complaints</h3>
<table>
    <thead>
        <tr>
            <th width="20%" class="grayed">Complaint Type</th>
            <th width="50%" class="grayed">Description</th>
            <th width="30%" class="grayed">Paid At</th>
        </tr>
    </thead>
    <tbody>
            $complaints_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">Trainer Ratings</h3>
<table>
    <thead>
        <tr>
            <th width="20%" class="grayed">Rating</th>
            <th width="50%" class="grayed">Review</th>
            <th width="30%" class="grayed">Created At</th>
        </tr>
    </thead>
    <tbody>
            $trainer_ratings_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">Membership Plan Activation Payments</h3>
<table>
    <thead>
        <tr>
            <th width="20%" class="grayed">Amount</th>
            <th width="50%" class="grayed">Plan Name</th>
            <th width="30%" class="grayed">Created At</th>
        </tr>
    </thead>
    <tbody>
            $membership_payments_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">BMI Records</h3>
<table>
    <thead>
        <tr>
            <th width="17.5%" class="grayed">BMI</th>
            <th width="17.5%" class="grayed">Weight</th>
            <th width="17.5%" class="grayed">Height</th>
            <th width="17.5%" class="grayed">Age</th>
            <th width="30%" class="grayed">Created At</th>
        </tr>
    </thead>
    <tbody>
            $bmi_records_html
    </tbody>
</table>
<br/>
<br/>
<h3 class="panelled">Workout Sessions</h3>
<table>
    <thead>
        <tr>
            <th width="20%" class="grayed">Workout</th>
            <th width="10%" class="grayed">Day</th>
            <th width="20%" class="grayed">Started At</th>
            <th width="20%" class="grayed">Ended At</th>
        </tr>
    </thead>
    <tbody>
            $workout_sessions_html
    </tbody>
</table>
HTML;

$file_name = "user data of $user->fname $user->lname";
$file_name = str_replace(" ", "_", strtolower($file_name)) . ".pdf";
generate_pdf($htmlBody, $file_name);
