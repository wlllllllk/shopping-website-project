<?php
include_once('lib/db.inc.php');

if (session_id() == "")
    session_start();

// get the order details
$products = json_decode($_GET['data'], true);

// get the username (email)
if (!empty($_SESSION['auth'])) {
    $customer_email = $_SESSION['auth']['email'];
} else {
    $customer_email = 'guest';
}

$date = date("d-m-Y h:i:s");
$currency = 'USD';
$merchant_email = 'sb-ujfkm15543764@business.example.com';
$salt = random_bytes(16);
$content = '';
$total_price = 0;
foreach ($products as $product) {
    if ($product['quantity'] > 0) {
        $price = ierg4210_prod_fetchOne($product['pid'])['PRICE'];
        $total_price += ($price * $product['quantity']);
        $content .= $product['pid'].';'.$product['quantity'].';'.$price.';';
    }
    else {
        throw new Exception("invalid-quantity");
    }
}

// generate and hash the digest
$digest = $currency.';'.$merchant_email.';'.$salt.';'.$content.$total_price;
$hashed_digest = hash_hmac('sha256', $digest, $salt);

// store the order into database
global $db;
$db = ierg4210_DB();

$sql="INSERT INTO ORDERS (USERNAME, DATE, DIGEST, CURRENCY, MERCHANT_EMAIL, SALT, CONTENT, TOTAL_PRICE) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";

$q = $db->prepare($sql);
$q->bindParam(1, $customer_email);
$q->bindParam(2, $date);
$q->bindParam(3, $hashed_digest);
$q->bindParam(4, $currency);
$q->bindParam(5, $merchant_email);
$q->bindParam(6, $salt);
$q->bindParam(7, $content);
$q->bindParam(8, $total_price);

$q->execute();

$lastId = $db->lastInsertId();

$result = '{"invoice": "'.$lastId.'", "custom": "'.$hashed_digest.'"}';
echo $result;