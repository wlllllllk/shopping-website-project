<?php
include_once('lib/db.inc.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

global $db;
$db = ierg4210_DB();

date_default_timezone_set('Asia/Hong_Kong');

// the IPN message from PayPal
$message = $_POST;

// reply to PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
$response = curl_exec($ch);
curl_close($ch);

$ok = false;
$error = 0;

// if PayPal returns VERIFIED
if ($response == 'VERIFIED') {

    // process the received data to generate the digest later
    $item_number = array();
    $quantity = array();
    $mc_gross = array();
    foreach ($message as $key => $value) {
        // pid
        if (strpos($key, 'item_number') !== false) {
            $item_number[$key] = preg_replace('/PID/', "", $value);
        }

        // quantity
        else if (strpos($key, 'quantity') !== false) {
            $quantity[$key] = $value;
        }

        // price
        else if (strpos($key, 'mc_gross_') !== false) {
            $mc_gross[$key] = $value;
        }
    }

    ksort($item_number, SORT_NATURAL);
    ksort($quantity, SORT_NATURAL);
    ksort($mc_gross, SORT_NATURAL);

    $item_number = array_values($item_number);
    $quantity = array_values($quantity);
    $mc_gross = array_values($mc_gross);

    $for_hash = '';
    for ($i = 0; $i < $message['num_cart_items']; $i++) {
        $for_hash .= $item_number[$i].';'.$quantity[$i].';'.$mc_gross[$i].';';
    }

    // first check if the payment status == Completed
    if ($message['payment_status'] == 'Completed') {

        // then check if the txn_id has not been previously processed
        // by looking for the txn_id record from database
        $sql = "SELECT * FROM ORDERS WHERE TRANSACTION_ID=?;";
        
        $q = $db->prepare($sql);
        $q->bindParam(1, $message['txn_id']);
        
        $result = '';
        if ($q->execute()) {
            $result = $q->fetch();
        
            // if the result is empty => txd_id has not been previously processed
            if ($result == '') {
                
                // check if txn_type is cart
                if ($message['txn_type'] == 'cart') {
                    // fetch the salt of this order from database
                    $sql = "SELECT * FROM ORDERS WHERE OID=?;";
                            
                    $q = $db->prepare($sql);
                    $q->bindParam(1, $message['invoice']);

                    $result = '';
                    if ($q->execute()) {
                        $result = $q->fetch();

                        // re-generate the digest
                        $digest = $message['mc_currency'].';'.$message['receiver_email'].';'.$result['SALT'].';'.$for_hash.$message['mc_gross'];
                        $hashed_digest = hash_hmac('sha256', $digest, $result['SALT']);

                        // the re-generated digest matched the original digest
                        // the IPN is legit
                        if ($hashed_digest == $result['DIGEST']) {
                            $ok = true;
                            $status = 'COMPLETED';
                            $date = date("d-m-Y H:i:s");

                            $sql = "UPDATE ORDERS SET STATUS=?, TRANSACTION_ID=?, TRANSACTION_TYPE=?, PAYMENT_TYPE=?, IPN=?, UPDATED=? WHERE OID=?;";
                            $q = $db->prepare($sql);
                            $q->bindParam(1, $status);
                            $q->bindParam(2, $message['txn_id']);
                            $q->bindParam(3, $message['txn_type']);
                            $q->bindParam(4, $message['payment_type']);
                            $q->bindParam(5, $response);
                            $q->bindParam(6, $date);
                            $q->bindParam(7, $message['invoice']);

                            $q->execute();
                        }
                        else {
                            $error = 3;
                        }
                    } 
                }
                else {
                    $error = 2;
                }
            }
        }
    }
    else {
        $error = 1;
    }
} 

// something went wrong
else {
    $status = 'PAYMENT FAILED';
    $date = date("d-m-Y H:i:s");

    $sql = "UPDATE ORDERS SET STATUS=?, TRANSACTION_ID=?, TRANSACTION_TYPE=?, PAYMENT_TYPE=?, IPN=?, UPDATED=? WHERE OID=?;";
    $q = $db->prepare($sql);
    $q->bindParam(1, $status);
    $q->bindParam(2, $message['txn_id']);
    $q->bindParam(3, $message['txn_type']);
    $q->bindParam(4, $message['payment_type']);
    $q->bindParam(5, $response);
    $q->bindParam(6, $date);
    $q->bindParam(7, $message['invoice']);

    $q->execute();

    $ok = true;
}

// something unexpected happened
if (!$ok) {
    if ($error == 1)
        $error_message = 'PAYMENT STATUS ERROR';
    else if ($error == 2)
        $error_message = 'TXN_TYPE ERROR';
    else if ($error == 3)
        $error_message = 'CONTENT ERROR';
    else 
        $error_message = 'UNEXPECTED ERROR';

    $date = date("d-m-Y H:i:s");

    $sql = "UPDATE ORDERS SET STATUS=?, TRANSACTION_ID=?, TRANSACTION_TYPE=?, PAYMENT_TYPE=?, IPN=?, UPDATED=? WHERE OID=?;";
    $q = $db->prepare($sql);
    $q->bindParam(1, $error_message);
    $q->bindParam(2, $message['txn_id']);
    $q->bindParam(3, $message['txn_type']);
    $q->bindParam(4, $message['payment_type']);
    $q->bindParam(5, $response);
    $q->bindParam(6, $date);
    $q->bindParam(7, $message['invoice']);

    $q->execute();
}

?>


