<?php
$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "/rat/index.php",
    ]
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <select class="input" name="type" required>
                <option value="">Select Complaint Type</option>
                <option value="facility">Facility Issues</option>
                <option value="staff">Staff Complaints</option>
                <option value="membership">Membership Issues</option>
                <option value="trainer">Trainer Performance</option>
                <option value="service">Service Quality</option>
                <option value="safety">Health and Safety Concerns</option>
                <option value="feedback">General Feedback</option>
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