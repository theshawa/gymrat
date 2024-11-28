<?php
$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "../",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="Facility Issues">Facility Issues</option>
                <option value="Staff Complaints">Staff Complaints</option>
                <option value="Customer Performance">Customer Performance</option>
                <option value="Service Quality">Service Quality</option>
                <option value="Health and Safety Concerns">Health and Safety Concerns</option>
                <option value="General Feedback">General Feedback</option>
            </select>
        </div>
        <div class="field">
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>
        <button class="btn">Submit</button>
    </form>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>