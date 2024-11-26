<?php

session_start();

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$originalEquipment = new Equipment();
$originalEquipment->fill([]);

$_SESSION['equipment'] = $originalEquipment;

redirect_with_success_alert("Revert Successful", "/staff/eq/equipments/create");
