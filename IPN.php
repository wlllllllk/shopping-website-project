<?php

header('HTTP/1.1 200 OK');


// $response = ''
// $paypalConfig = [
//     //'email' => 'user@example.com',
//     'return_url' => 'https://secure.s67.ierg4210.ie.cuhk.edu.hk/index.php',
//     'cancel_url' => 'https://secure.s67.ierg4210.ie.cuhk.edu.hk/login.php',
//     'notify_url' => 'https://secure.s67.ierg4210.ie.cuhk.edu.hk/IPN.php'
// ];

// if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {
//     $data = [];
//     foreach ($_POST as $key => $value) {
//         $data[$key] = stripslashes($value);
//     }

//     $data['return'] = stripslashes($paypalConfig['return_url']);
//     $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
//     $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

//     $queryString = http_build_query($data);

//     header('location:' . $paypalUrl . '?' . $queryString);
//     exit();
// }
// else {
//     throw new Exception("IPN");
// }


?>