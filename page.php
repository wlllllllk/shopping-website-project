<?php
include_once('lib/db.inc.php');

global $db;
$db = ierg4210_DB();

$q = $db->prepare("SELECT COUNT(*) FROM PRODUCTS;");

if ($q->execute()) 
    echo json_encode($q->fetch());

?>
