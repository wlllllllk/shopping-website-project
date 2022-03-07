<?php
include_once('lib/db.inc.php');

if (!preg_match('/^\d*$/', $_GET["page"]))
    throw new Exception("invalid-pageno");
$_GET["page"] = (int) $_GET["page"];

$page = $_GET["page"];

$record_per_page = 12;

$start_pos = ($page - 1) * $record_per_page;

global $db;
$db = ierg4210_DB();

$q = $db->prepare("SELECT * FROM PRODUCTS LIMIT ?, ?;");
$q->bindParam(1, $start_pos);
$q->bindParam(2, $record_per_page);

if ($q->execute()) 
    echo json_encode($q->fetchAll());

?>
