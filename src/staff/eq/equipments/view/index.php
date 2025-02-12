<?php
$id = $_GET['id'] ?? null;

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$equipment = new Equipment();
try {
    $equipment->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp/exercises");
}
$_SESSION['equipment'] = $equipment;

$sidebarActive = 2;

$menuBarConfig = [
    "title" => $equipment->name,
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Equipment", "href" => "/staff/eq/equipments/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Equipment", "href" => "/staff/eq/equipments/delete/index.php?id=$id", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
$pageConfig['styles'][] = "./equipmentView.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";


require_once "../../../../auth-guards.php";
auth_required_guard_with_role("eq", "/staff/login");
?>

<main>
    <div class="base-container">

        <?php require_once "../../../includes/menubar.php"; ?>

        <div class="view-equipment-container">
            <div>
                <h2 style="margin-bottom: 20px;">
                    Equipment Details
                </h2>
                <div>
                    <div class="view-equipment-details">
                        <p>ID</p>
                        <p class="alt"><?= $equipment->id ?></p>
                    </div>
                    <hr>
                    <div class="view-equipment-details">
                        <p>Type</p>
                        <p class="alt"><?= $equipment->type ?></p>
                    </div>
                    <hr>
                    <div class="view-equipment-details">
                        <p>Manufacturer</p>
                        <p class="alt"><?= $equipment->manufacturer ?></p>
                    </div>
                    <hr>
                    <div class="view-equipment-details">
                        <p>Purchase Date</p>
                        <p class="alt"><?= $equipment->purchase_date->format('Y-m-d H:i:s') ?></p>
                    </div>
                    <hr>
                    <div class="view-equipment-details">
                        <p>Last Maintenance</p>
                        <p class="alt"><?= $equipment->last_maintenance->format('Y-m-d H:i:s') ?></p>
                    </div>
                </div>
            </div>
            <div>
                <div style="margin: 10px" class="img">
                    <h2 style="margin-bottom: 20px;">
                        Product Image
                    </h2>
                    <?php if ($equipment->image): ?>
                        <img src="../../../../<?= $equipment->image ?>" alt="<?= $equipment->name ?>">
                    <?php else: ?>
                        <img src="./default-image.jpg" alt="default">
                    <?php endif ?>

                </div>
                <div style="margin: 10px">
                    <h2 style="margin-bottom: 20px;">
                        Description
                    </h2>
                    <p><?= $equipment->description ?></p>
                </div>
            </div>
            <!-- Equipment details container -->
            <!--        <div class="view-equipment-container">-->
            <!---->
            <!--            <div>-->
            <!--                 Equipment Image-->
            <!--                --><?php //if ($equipment['img']): 
                                    ?>
            <!--                    <img src="--><?php //= htmlspecialchars($equipment['img']) 
                                                    ?><!--" alt="--><?php //= htmlspecialchars($equipment['name']) 
                                                                    ?><!--" style="width: 100%; max-width: 400px; margin-bottom: 20px;">-->
            <!--                --><?php //else: 
                                    ?>
            <!--                    <p>No image available</p>-->
            <!--                --><?php //endif; 
                                    ?>
            <!---->
            <!--                Equipment Details-->
            <!--                 <div class="equipment">-->
            <!--                     <h2 style="margin-bottom: 20px;">Equipment Details</h2>-->
            <!--                     <ul>-->
            <!--                         <li><strong>ID:</strong> --><?php //= htmlspecialchars($equipment['id']) 
                                                                        ?><!--</li>-->
            <!--                         <li><strong>Name:</strong> --><?php //= htmlspecialchars($equipment['name']) 
                                                                        ?><!--</li>-->
            <!--                         <li><strong>Type:</strong> --><?php //= htmlspecialchars($equipment['type']) 
                                                                        ?><!--</li>-->
            <!--                         <li><strong>Last Maintenance:</strong> --><?php //= htmlspecialchars($equipment['last_maintenance']) 
                                                                                    ?><!--</li>-->
            <!--                         <li><strong>Purchase Date:</strong> --><?php //= htmlspecialchars($equipment['purchase_date']) 
                                                                                ?><!--</li>-->
            <!--                         <li><strong>Manufacturer:</strong> --><?php //= htmlspecialchars($equipment['manufacturer']) 
                                                                                ?><!--</li>-->
            <!--                     </ul>-->
            <!---->
            <!--                 </div>-->
            <!--            </div>-->
            <!---->
            <!--            Equipment Description-->
            <!--            <div class="description">-->
            <!--                <h2 style="margin-bottom: 20px;">Description</h2>-->
            <!--                <p>--><?php //= htmlspecialchars($equipment['description']) 
                                        ?><!--</p>-->
            <!--            </div>-->
            <!--        </div>-->
        </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>