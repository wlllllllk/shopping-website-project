<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

// get the message
// foreach ($_POST as $parm => $var) {
//     $var = urlencode(stripslashes($var));
//     $resp .= "&$parm=$var";
// }
$message = $_POST;

// reply to PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&123" . http_build_query($_POST));
$response = curl_exec($ch);
curl_close($ch);

include_once('lib/db.inc.php');

global $db;
$db = ierg4210_DB();



$oid = 1;
$sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";
$q = $db->prepare($sql);
$q->bindParam(1, $message['txn_id']);
$q->bindParam(2, $oid);
$q->execute();


$oid = 2;
$sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";
$q = $db->prepare($sql);
$q->bindParam(1, $response);
$q->bindParam(2, $oid);

$q->execute();



// //
// // STEP 1 - be polite and acknowledge PayPal's notification
// //

// header('HTTP/1.1 200 OK');

// $status = "STEP 1";
// $oid = 1;

// $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

// $q = $db->prepare($sql);
// $q->bindParam(1, $status);
// $q->bindParam(2, $oid);

// $q->execute();

// //
// // STEP 2 - create the response we need to send back to PayPal for them to confirm that it's legit
// //

// $resp = 'cmd=_notify-validate';
// foreach ($_POST as $parm => $var) {
//     $var = urlencode(stripslashes($var));
//     $resp .= "&$parm=$var";
// }

// $status = "STEP 2";
// $oid = 1;

// $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

// $q = $db->prepare($sql);
// $q->bindParam(1, $resp);
// $q->bindParam(2, $oid);

// $q->execute();

// // STEP 3 - Extract the data PayPal IPN has sent us, into local variables 

// $item_name        = $_POST['item_name'];
// $item_number      = $_POST['item_number'];
// $payment_status   = $_POST['payment_status'];
// $payment_amount   = $_POST['mc_gross'];
// $payment_currency = $_POST['mc_currency'];
// $txn_id           = $_POST['txn_id'];
// $receiver_email   = $_POST['receiver_email'];
// $payer_email      = $_POST['payer_email'];
// $record_id	 	  = $_POST['custom'];


// // $status = "STEP 3";
// // $oid = 1;

// // $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

// // $q = $db->prepare($sql);
// // $q->bindParam(1, $status);
// // $q->bindParam(2, $oid);

// // $q->execute();


// // Right.. we've pre-pended "cmd=_notify-validate" to the same data that PayPal sent us (I've just shown some of the data PayPal gives us. A complete list
// // is on their developer site.  Now we need to send it back to PayPal via HTTP.  To do that, we create a file with the right HTTP headers followed by 
// // the data block we just created and then send the whole bally lot back to PayPal using fsockopen


// // STEP 4 - Get the HTTP header into a variable and send back the data we received so that PayPal can confirm it's genuine

// $httphead = "POST /cgi-bin/webscr HTTP/1.0\r\n";
// $httphead .= "Content-Type: application/x-www-form-urlencoded\r\n";
// $httphead .= "Content-Length: " . strlen($resp) . "\r\n";
// $httphead .= "User-Agent: PHP-IPN-VerificationScript\r\n\r\n";


// // $status = "STEP 4";
// // $oid = 1;

// // $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

// // $q = $db->prepare($sql);
// // $q->bindParam(1, $status);
// // $q->bindParam(2, $oid);

// // $q->execute();


// // Now create a ="file handle" for writing to a URL to paypal.com on Port 443 (the IPN port)

// $errno ='';
// $errstr='';

// $fh = fsockopen('ssl://ipnpb.sandbox.paypal.com', 443, $errno, $errstr, 30);

// // STEP 5 - Nearly done.  Now send the data back to PayPal so it can tell us if the IPN notification was genuine

// if (!$fh) {
//     // header('Location: login.php?error=3');
//     // exit();
//     $status = "STEP 5.1";
//     $oid = 1;

//     $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

//     $q = $db->prepare($sql);
//     $q->bindParam(1, $status);
//     $q->bindParam(2, $oid);

//     $q->execute();

// // Uh oh. This means that we have not been able to get thru to the PayPal server.  It's an HTTP failure
// //
// // You need to handle this here according to your preferred business logic.  An email, a log message, a trip to the pub..
// } 

// // Connection opened, so spit back the response and get PayPal's view whether it was an authentic notification		   

// else {

//     $status = $fh;
//     $oid = 1;

//     $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

//     $q = $db->prepare($sql);
//     $q->bindParam(1, $status);
//     $q->bindParam(2, $oid);

//     $q->execute();

//     fwrite($fh, $httphead.$resp);

//     while (!feof($fh)) {
//         $readresp = fgets($fh);

//         $status = $readresp;
//         $oid = 2;
    
//         $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";
    
//         $q = $db->prepare($sql);
//         $q->bindParam(1, $status);
//         $q->bindParam(2, $oid);
    
//         $q->execute();

//         if (strcmp($readresp, "VERIFIED") == 0) {
//             $status = "VERIFIED";
//             $oid = 1;

//             $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

//             $q = $db->prepare($sql);
//             $q->bindParam(1, $status);
//             $q->bindParam(2, $oid);
            
//             $q->execute();

//             header('Location: login.php?error=1');
//             exit();
//         }

//         else if (strcmp($readresp, "INVALID") == 0) {
//             $status = "INVALID";
//             $oid = 2;

//             $sql = "UPDATE ORDERS SET STATUS=? WHERE OID=?;";

//             $q = $db->prepare($sql);
//             $q->bindParam(1, $status);
//             $q->bindParam(2, $oid);
            
//             $q->execute();

//         //  			Man alive!  A hacking attempt?
//             header('Location: login.php?error=2');
//             exit();
//         }
//     }
//     fclose($fh);
// }
// //
// //
// // STEP 6 - Pour yourself a cold one.
// //
// //

?>


