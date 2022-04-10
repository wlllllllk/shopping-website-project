<?php
    require __DIR__.'/lib/db.inc.php';
    if (session_id() == "")
        session_start();
    
    $customer_name = "Welcome, ";
    if (!empty($_SESSION['auth'])) {
        $name = substr($_SESSION['auth']['email'], 0, strrpos($_SESSION['auth']['email'],"@"));
        $customer_name .= htmlspecialchars($name);
    } 
    else {
        $customer_name .= "Guest";
    }

    $image = './icon/question.svg';
    $heading = 'Something Went Wrong';
    $message = 'Please contact our customer support.';
    $ref = 'NA';
    $reminder = 'Please save it securely, you can use this to trace your order inside Member Portal';
    if (isset($_REQUEST["status"])) {
        $status = $_REQUEST["status"];
        if ($status == 1) {
            $image = './icon/yes.svg';
            $heading = 'Order Placed Successfully';
            $message = 'We\'ll keep you updated!';
        }
        else if ($status == 2) {
            $image = './icon/no.svg';
            $heading = 'Payment Failed';
            $message = 'Please try again or contact us for help.';
        }
        else if ($status == 3) {
            $image = './icon/no.svg';
            $heading = 'Order Cancelled';
            $message = 'You can place the order again at anytime :)';
        }
        else if ($status == 4) {
            $image = './icon/no.svg';
            $heading = 'There is nothing in your cart :(';
            $message = 'Try add something and come back again.';
            $reminder = '';
        }

        if (isset($_REQUEST["ref"])) {
            $ref = $_REQUEST["ref"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Result</title>
    <link rel="shortcut icon" type="image/svg" href="./icon/favicon.svg">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="../css/result.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <div class="searchBar"><input type="search" placeholder="Just a decoration..."></div>
            <div class="welcome"><?php echo $customer_name; ?></div>
            <div class="actions">
                <div class="shopping-list">
                    <button>Shopping List &#40;0&#41;</button>
                    <div class="container">
                        <div class="contents">
                            <div class="top">                            
                                <h3>Shopping List</h3>
                                <h4 id="clear">Clear ALL</h4>
                            </div>
                            <h4 id="nothing">There is nothing here :&#40;</h4>
                            <ul>
                                <template id="cart-item-template">
                                    <li>
                                        <div class="details">
                                            <a href="">
                                                <div class="photo"><img src="" alt=""></div>
                                            </a>
                                            <div class="text">
                                                <span class="name"></span>
                                                <div>
                                                    <input class="quantity" type="number" value="">
                                                    <span class="price"></span>
                                                </div>
                                            </div>
                                            <div class="delete" data-pid="">&#10799;</div>
                                        </div>
                                    </li> 
                                </template>
                            </ul>
                            <div class="bottom">
                                <span class="price">Total: $0</span>
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account">
                    <a href="./portal.php">
                        <button>Member Portal</button>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <div class="main">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="">
        <h1><?php echo htmlspecialchars($heading); ?></h1>
        <h2><?php echo htmlspecialchars($message); ?></h2>
        <h3>Ref: <?php echo htmlspecialchars($ref); ?></h3>
        <h3><?php echo htmlspecialchars($reminder); ?></h3>
        <a href="./index.php"><button>Continue Shopping</button></a>
    </div>

    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AZGNpMt6WA_89LMw5ULbX6xwtcQiVgh__Tw8q_XOeGqLRHZ_Ijtf90qNeQNGQSud9ZAk9W1h4fOeEKBl&currency=USD"></script>
    <script type="text/javascript" src="../js/payment.js"></script>
    <script type="text/javascript" src="../js/cart.js"></script>
</body>

</html>