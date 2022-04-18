<?php
    if (session_id() == "")
        session_start();

    require __DIR__.'/lib/db.inc.php';
    include_once("./nonce.php");
    include_once('./auth.php');

    $search_form = '<fieldset>
                        <legend>Search for An Order</legend>
                        <form onsubmit="return searchOrder(this)">
                            <label for="name-add-product">Order Ref</label>
                            <input type="text" name="ORDER" id="order" pattern="^[\w]+$" required="true">
                            <div class="actions">
                                <input type="reset" value="Reset">
                                <input type="submit" value="Submit">
                            </div>      
                        </form>
                    </fieldset>';
    $change_password_form = '';
    $bottom_button = '';
    $heading = '';
    $orders = '';
    $table = '';
    $table_content = '';

    $customer_email = '';
    if (!empty($_SESSION['auth'])) {
        $customer_email = $_SESSION['auth']['email'];
    } 
    else {
        $customer_email = "guest";
    }

    // search for an specific order
    if (isset($_REQUEST['ref'])) {
        $orders = ierg4210_order_fetch_by_ref($_REQUEST['ref']);
        if (empty($orders) || $orders[0]['USERNAME'] != $customer_email) {
            $heading = 'Order doesn\'t exist';
        } 
        else {
            $heading = 'Order for '.$_REQUEST['ref'];
            $table =    '<table>
                            <tr>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Created</th>
                                <th>Last Updated</th>
                            </tr>';
            $table_content = '';
            
            foreach ($orders as $order) {
                $product_list = json_decode($order['PRODUCT_LIST']);
                $price_list = json_decode($order['INDIVIDUAL_PRICES']);
        
                $sub = '';
                $i = 0;
                for ($i = 0; $i < count($product_list); $i++) {
                    $product = ierg4210_prod_fetchOne($product_list[$i]->pid);
                    $sub .=     '<tr class="contents">
                                    <td>'.$product_list[$i]->pid.'</td>
                                    <td>'.$product['NAME'].'</td>
                                    <td>$'.$price_list[$i].'</td>
                                    <td>'.$product_list[$i]->quantity.'</td>
                                </tr>';
                }
        
                $table_content .=    '<tr class="title">
                                        <td>'.$order['OID'].'</td>
                                        <td>'.$order['STATUS'].'</td>
                                        <td>'.$order['TRANSACTION_ID'].'</td>
                                        <td>$'.$order['TOTAL_PRICE'].'</td>
                                        <td>'.$order['USERNAME'].'</td>
                                        <td>'.$order['CREATED'].'</td>
                                        <td>'.$order['UPDATED'].'</td>
                                    </tr>
                                    <tr class="details">
                                        <td colspan="7">
                                            <table>
                                                <tr>
                                                    <td>Product ID</td>
                                                    <td>Name</td>
                                                    <td>Price</td>
                                                    <td>Quantity</td>
                                                </tr>
                                                '.$sub.'
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7"><br></td>
                                    </tr>
                                    ';
            }
            $table .= $table_content.'</table>';
        }
    }

    // logged in
    if (!empty($_SESSION['auth'])) {
        $email = $_SESSION['auth']['email'];
        $change_password_form = '<fieldset id="password-change-form">
                                    <legend>Change Password</legend>
                                    <form method="POST" action="auth-process.php?action='.($action = "change").'"
                                        onsubmit="return check_input(this);">
                                        <label for="current-password">Current Password</label>
                                        <input type="password" name="CURRENT-PASSWORD" id="current-password" pattern="^.+$"
                                            placeholder="Enter your current password here" required="true">
                                        <label for="new-password">New Password</label>
                                        <input type="password" name="NEW-PASSWORD" id="new-password" pattern="^.+$"
                                            placeholder="Enter your new password here" required="true">
                                        <div class="actions">
                                            <input type="reset" value="Reset">
                                            <input type="submit" value="Submit">
                                        </div>
                                        <input type="hidden" name="nonce" value="'.csrf_getNouce($action).'">
                                    </form>
                                </fieldset>';

        $bottom_button =   '<form method="POST" action="auth-process.php?action='.($action = "logout").'">
                                <input class="logout" type="submit" value="Logout">
                                <input type="hidden" name="nonce" value="'.csrf_getNouce($action).'">
                            </form>';

        // fetch 5 most recent orders
        if (!isset($_REQUEST['ref'])) {
            $orders = ierg4210_order_fetch_by_email($email);
            if (empty($orders)) {
                $heading = 'You don\'t have any order :(';
                $search_form = '';
            } 
            else {
                $heading = 'Your 5 Most Recent Orders';
                $table =    '<table>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Customer</th>
                                    <th>Created</th>
                                    <th>Last Updated</th>
                                </tr>';
                $table_content = '';

                foreach ($orders as $order) {
                    $product_list = json_decode($order['PRODUCT_LIST']);
                    $price_list = json_decode($order['INDIVIDUAL_PRICES']);
            
                    $sub = '';
                    $i = 0;
                    for ($i = 0; $i < count($product_list); $i++) {
                        $product = ierg4210_prod_fetchOne($product_list[$i]->pid);
                        $sub .=     '<tr class="contents">
                                        <td>'.$product_list[$i]->pid.'</td>
                                        <td>'.$product['NAME'].'</td>
                                        <td>$'.$price_list[$i].'</td>
                                        <td>'.$product_list[$i]->quantity.'</td>
                                    </tr>';
                    }
            
                    $table_content .=   '<tr class="title">
                                            <td>'.$order['OID'].'</td>
                                            <td>'.$order['STATUS'].'</td>
                                            <td>'.$order['TRANSACTION_ID'].'</td>
                                            <td>$'.$order['TOTAL_PRICE'].'</td>
                                            <td>'.$order['USERNAME'].'</td>
                                            <td>'.$order['CREATED'].'</td>
                                            <td>'.$order['UPDATED'].'</td>
                                        </tr>
                                        <tr class="details">
                                            <td colspan="7">
                                                <table>
                                                    <tr>
                                                        <td>Product ID</td>
                                                        <td>Name</td>
                                                        <td>Price</td>
                                                        <td>Quantity</td>
                                                    </tr>
                                                    '.$sub.'
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"><br></td>
                                        </tr>';
                }
                $table .= $table_content.'</table>';
            }
        }
    }

    // guest user
    else {
        if (!isset($_REQUEST['ref']))
            $heading = "Search for An Order";
        $bottom_button = '<a href="./login.php"><button class="login">Login</button></a>';
    }

    $admin_button = '';
    if (auth()) {
        $admin_button = '<div><a href="./admin.php"><button>Admin Panel</button></a></div>';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Member Portal</title>
    <link rel="shortcut icon" type="image/svg" href="./icon/favicon.svg">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/portal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Member Portal</h1>
            <div class="actions">
                <?php echo $admin_button; ?>
                <div class="account">
                    <a href="./index.php">
                        <button>Leave</button>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="main">
        <div class="orders">
            <h1><?php echo $heading; ?></h1>
            <?php echo $search_form; ?>                
            <?php echo $table; ?>                
        </div>

        <div class="second">
            <h1>Account Actions</h1>
            <div>
                <?php echo $change_password_form; ?>                
                <?php echo $bottom_button; ?>   
            </div>
        </div>
    </div>
    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>

    <script type="text/javascript" src="../js/login.js"></script>
    <script type="text/javascript" src="../js/portal.js"></script>
</body>

</html>