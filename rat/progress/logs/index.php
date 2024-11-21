<?php
$pageConfig = [
    "title" => "My Progress",
    "titlebar" => [
        "back_url" => "../../"
    ]
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";

?>

<main>
    <?php
    $subnavbarConfig = [
        'links' => [
            [
                'title' => 'BMI',
                'href' => '../'
            ],
            [
                'title' => 'Trainer Logs',
                'href' => './'
            ]
        ],
        'active' => 1
    ];

    require_once "../../includes/subnavbar.php"; ?>
    <p>Trainer Logs</p>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>