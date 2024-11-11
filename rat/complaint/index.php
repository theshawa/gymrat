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
    <p class="paragraph" style="margin-bottom: 20px;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nam quas modi, sequi, velit aliquid earum dolorum saepe repellat porro harum perferendis iure autem. Natus, sint. At impedit modi pariatur iure!</p>
    <form class="form" action="complaint_process.php" method="post">
        <div class="field">
            <label for="type">Complaint Type</label>
            <select class="input" name="type" required>
                <option value="">-- Select Complaint Type --</option>
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
            <label for="description">Description</label>
            <textarea class="input" name="description" required placeholder="Description"></textarea>
        </div>
        <button class="btn">Submit</button>
    </form>
</main>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>