<?php
// connect to the database
// TODO: change the following path if needed
// Warning: NEVER put your db in a publicly accessible location
$db = new PDO('sqlite:/var/www/cart.db');

// enable foreign key support
$db->query('PRAGMA foreign_keys = ON;');

// FETCH_ASSOC:
// Specifies that the fetch method shall return each row as an
// array indexed by column name as returned in the corresponding
// result set. If the result set contains multiple columns with
// the same name, PDO::FETCH_ASSOC returns only a single value
// per column name.
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if (!preg_match('/^\d*$/', $_GET['pid']))
    throw new Exception("invalid-pid");
$_GET['pid'] = (int) $_GET['pid'];

$pid = $_GET["pid"];

$sql = "SELECT * FROM PRODUCTS WHERE PID=?;";

$q = $db->prepare($sql);
$q->bindParam(1, $pid);
$q->execute();

if ($q->execute())
    return $q->fetch();
?>
