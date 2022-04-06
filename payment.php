<?php
include_once('lib/db.inc.php');

// set the default timezone to use.
date_default_timezone_set('Asia/Hong_Kong');

if (session_id() == "")
    session_start();

if (isset($_GET['data'])) {
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
    $individual_prices = array();
    $total_price = 0;

    $for_hash = '';

    $items = array();
    $item = array();
    foreach ($products as $product) {
        if ($product['quantity'] > 0) {
            $current_product = ierg4210_prod_fetchOne($product['pid']);

            $for_hash .= $product['pid'].';'.$product['quantity'].';'.number_format($current_product['PRICE'], 2, '.', '').';';

            array_push($individual_prices, $current_product['PRICE']);

            $total_price += ($current_product['PRICE'] * $product['quantity']);

            $item['name'] = $current_product['NAME'];
            $item['sku'] = 'PID'.$product['pid'];
            $item['unit_amount'] = array('currency_code'=>'USD', 'value'=>number_format($current_product['PRICE'], 2, '.', ''));
            $item['quantity'] = $product['quantity'];
            array_push($items, $item);
        }
        else {
            throw new Exception("invalid-quantity");
        }
    }

    $total_price = number_format($total_price, 2, '.', '');

    // generate and hash the digest
    $digest = $currency.';'.$merchant_email.';'.$salt.';'.$for_hash.$total_price;
    $hashed_digest = hash_hmac('sha256', $digest, $salt);

    // store the order into database
    global $db;
    $db = ierg4210_DB();

    $sql="INSERT INTO ORDERS (DIGEST, TOTAL_PRICE, CURRENCY, MERCHANT_EMAIL, SALT, PRODUCT_LIST, INDIVIDUAL_PRICES, STATUS, USERNAME, DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $status = 'CREATED';

    $products = json_encode($products);
    $individual_prices = json_encode($individual_prices);

    $q = $db->prepare($sql);
    $q->bindParam(1, $hashed_digest);
    $q->bindParam(2, $total_price);
    $q->bindParam(3, $currency);
    $q->bindParam(4, $merchant_email);
    $q->bindParam(5, $salt);
    $q->bindParam(6, $products);
    $q->bindParam(7, $individual_prices);
    $q->bindParam(8, $status);
    $q->bindParam(9, $customer_email);
    $q->bindParam(10, $date);

    $q->execute();

    $lastId = $db->lastInsertId();

    $amount = array();
    $amount['currency_code'] = 'USD';
    $amount['value'] = $total_price;
    $amount['breakdown'] = array('item_total'=>array('currency_code'=>'USD', 'value'=>$total_price));

    $temp = array('amount'=>$amount, 'items'=>$items, 'invoice_id'=>$lastId, 'custom_id'=>$hashed_digest);

    $purchase_units = array();
    array_push($purchase_units, $temp);

    $response = array('purchase_units'=>$purchase_units);

    $final_response = array();
    array_push($final_response, $response, array('id'=>$lastId));

    echo json_encode($final_response);
}
