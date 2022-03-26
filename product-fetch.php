<?php
include_once('lib/db.inc.php');

$page = $_GET['page'];
$record_per_page = $_GET['num'];

$sanitized_page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
$sanitized_record_per_page = filter_var($record_per_page, FILTER_SANITIZE_NUMBER_INT);

if (!preg_match('/^\d*$/', $sanitized_page) || !filter_var($sanitized_page, FILTER_VALIDATE_INT))
    throw new Exception("invalid-page-num");
$sanitized_page = (int) $sanitized_page;
if (!preg_match('/^\d*$/', $sanitized_record_per_page) || !filter_var($sanitized_record_per_page, FILTER_VALIDATE_INT))
    throw new Exception("invalid-record-num");
$sanitized_record_per_page = (int) $sanitized_record_per_page;

$start_pos = ($sanitized_page - 1) * $sanitized_record_per_page;

global $db;
$db = ierg4210_DB();

$q = $db->prepare("SELECT * FROM PRODUCTS LIMIT ?, ?;");
$q->bindParam(1, $start_pos);
$q->bindParam(2, $sanitized_record_per_page);

if ($q->execute()) 
    echo json_encode($q->fetchAll());

?>
