<footer>

</footer>
<?php
$pageScripts = [];
if (isset($pageConfig)) {
    $pageScripts = $pageConfig['scripts'] ?? [];
}
foreach ($pageScripts as $script) {
    echo "<script src='$script' type='text/javascript'></script>";
}
?>
</body>

</html>