<?php
include_once('lib/db.inc.php');

$pid = $_GET['pid'];

$sanitized_pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);

if (!preg_match('/^\d*$/', $sanitized_pid))
    throw new Exception("invalid-pid");
$sanitized_pid = (int) $sanitized_pid;

global $db;
$db = ierg4210_DB();

$sql = "SELECT * FROM PRODUCTS WHERE PID=?;";

$q = $db->prepare($sql);
$q->bindParam(1, $sanitized_pid);
$q->execute();

if ($q->execute()) 
    echo json_encode($q->fetch());

?>
