<?php
include_once('lib/db.inc.php');

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

    $items = array();
    $item = array();
    foreach ($products as $product) {
        if ($product['quantity'] > 0) {
            $current_product = ierg4210_prod_fetchOne($product['pid']);
            array_push($individual_prices, $current_product['PRICE']);

            $total_price += ($current_product['PRICE'] * $product['quantity']);
            //$content .= $product['pid'].';'.$product['quantity'].';'.$current_product['PRICE'].';';

            $item['name'] = $current_product['NAME'];
            $item['unit_amount'] = array('currency_code'=>'USD', 'value'=>$current_product['PRICE']);
            $item['quantity'] = $product['quantity'];
            array_push($items, $item);
        }
        else {
            throw new Exception("invalid-quantity");
        }
    }

    $amount = array();
    $amount['currency_code'] = 'USD';
    $amount['value'] = $total_price;
    $amount['breakdown'] = array('item_total'=>array('currency_code'=>'USD', 'value'=>$total_price));

    $temp = array('amount'=>$amount, 'items'=>$items);

    $purchase_units = array();
    array_push($purchase_units, $temp);

    $response = array('purchase_units'=>$purchase_units);

    // generate and hash the digest
    $digest = $currency.';'.$merchant_email.';'.$salt.';'.json_encode($products).';'.json_encode($individual_prices).';'.$total_price;
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

    $final_response = array();
    array_push($final_response, $response, array('id'=>$lastId));

    echo json_encode($final_response);
    // $result = '{"invoice": "'.$lastId.'", "custom": "'.$hashed_digest.'"}';
    // // echo $result;
}

if (isset($_GET['update'])) {
    $order = $_GET['update'];
    $new = $_GET['new'];

    // store the order into database
    global $db;
    $db = ierg4210_DB();

    $sql="UPDATE ORDERS SET ORDER_ID=? WHERE OID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $order);
    $q->bindParam(2, $new);

    if ($q->execute())
        echo "success";
    else
        echo "failed";
}
