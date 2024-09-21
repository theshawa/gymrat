<?php
$pageConfig = [
    "title" => "Make Complaint",
    "titlebar" => [
        "back_url" => "/index.php",
    ]
];

include_once "../includes/header.php";
include_once "../includes/titlebar.php";
?>

<main>
    <p class="paragraph" style="margin-bottom: 20px;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nam quas modi, sequi, velit aliquid earum dolorum saepe repellat porro harum perferendis iure autem. Natus, sint. At impedit modi pariatur iure!</p>
    <form class="form" action="complaint_process.php" method="post">
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
        <textarea class="input" name="description" required placeholder="Description"></textarea>
        <button class="btn">Submit</button>
    </form>
</main>

<?php include_once "../includes/navbar.php" ?>
<?php include_once "../includes/footer.php" ?>