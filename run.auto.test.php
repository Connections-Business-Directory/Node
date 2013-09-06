<?php //ä
include("unit.test.php");

$results = unit::loadTests("tests","autobuilder");
echo json_encode($results,JSON_FORCE_OBJECT);

?>