<?php
require __DIR__.'/lib/db.inc.php';
$categories = ierg4210_cat_fetchAll();
$li_cat = '';
foreach ($categories as $value_cat) {
    $li_cat .= '<li><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
}

$products = ierg4210_prod_fetchAll();
$div_prod = '';
foreach ($products as $value_prod) {
    $div_prod .= '<div class="product">
                    <a href="product.php?pid='.$value_prod["PID"].'">
                        <div class="photo"><img src="'.$value_prod["THUMBNAIL"].'" alt="" /></div>
                    </a>
                    <div class="text">
                        <a href="product.php?pid='.$value_prod["PID"].'">
                            <div class="name">'.$value_prod["NAME"].'</div>
                        </a>
                        <div class="price">$'.$value_prod["PRICE"].'</div>
                    </div>
                    <button>Add to Cart</button>
                </div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/main.css">
    <script defer src="../js/cart.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav>
            <a href="index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <div class="searchBar"><input type="text" placeholder="Type to search..."></div>
            <div class="actions">
                <div class="shopping-list">
                    <button>Shopping List</button>
                    <div class="container">
                        <div class="contents">
                            <h3>Shopping List</h3>
                            <ul>
                                <!-- <li>
                                    <div class="details">
                                        <a href="product.php?pid=7">
                                            <div class="photo"><img src="./images/7.jpg" alt=""></div>
                                        </a>
                                        <div class="text">
                                            <span class="name">Doge</span>
                                            <div>
                                                <input type="number" value="1">
                                                <span class="price">$8700</span>
                                            </div>
                                        </div>
                                    </div>
                                </li> -->
                            </ul>
                            <div class="bottom">
                                <span class="price">Total: $0</span>
                                <button>Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account">
                    <a href="./admin.php">
                        <button>Admin</button>
                    </a>
                </div>
            </div>
        </nav>
        <div class="categories">
            <h3>Categories</h3>
            <ul>
                <?php echo $li_cat; ?>
            </ul>
            <div class="selector">
                <a href="#first">&lt;</a>
                <a href="#last">&gt;</a>
            </div>
        </div>
    </header>

    <div class="main">
        <div class="location"><a href="index.php">Home</a></div>
        <section>
            <h3>All Products</h3>
            <div class="product-list">
                <?php echo $div_prod; ?>
            </div>
        </section>
    </div>

    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
</body>

</html>