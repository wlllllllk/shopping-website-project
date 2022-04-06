<?php
include_once('lib/db.inc.php');

global $db;
$db = ierg4210_DB();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

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
            // also check if txn_type is cart
            if ($result == '' && $message['txn_type'] == 'cart') {
        
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

                        $sql = "UPDATE ORDERS SET STATUS=?, TRANSACTION_ID=?, TRANSACTION_TYPE=?, PAYMENT_TYPE=? WHERE OID=?;";
                        $q = $db->prepare($sql);
                        $q->bindParam(1, $response);
                        $q->bindParam(2, $message['txn_id']);
                        $q->bindParam(3, $message['txn_type']);
                        $q->bindParam(4, $message['payment_type']);
                        $q->bindParam(5, $message['invoice']);
            
                        $q->execute();
                    }
                } 
            }
        }
    }
} 

// something went wrong
else {
    $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";
    $q = $db->prepare($sql);
    $q->bindParam(1, $response);
    $q->bindParam(2, $message['invoice']);

    $q->execute();

    $ok = true;
}

// something unexpected happened
if (!$ok) {
    $response = 'FAILED';
    $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";
    $q = $db->prepare($sql);
    $q->bindParam(1, $response);
    $q->bindParam(2, $message['invoice']);

    $q->execute();
}

?>


