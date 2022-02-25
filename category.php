<?php
require __DIR__.'/lib/db.inc.php';
$catid = $_REQUEST["catid"];
$categories = ierg4210_cat_fetchAll();
$li_cat = '';
$current_cat = '';
foreach ($categories as $value_cat) {
    if ($value_cat["CATID"] == $catid) {
        $li_cat .= '<li class="selected"><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
        $current_cat = $value_cat["NAME"];
    } else {
        $li_cat .= '<li><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
    }
}

$products = ierg4210_prod_fetch_by_catid($catid);
$div_prod = '';
foreach ($products as $value_prod) {
    $div_prod .= '<div class="product">
                    <a href="product.php?pid='.$value_prod["PID"].'">
                        <div class="photo"><img src="./images/thumbnails/'.$value_prod["PID"].'_thumbnail.jpg" alt="" /></div>
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
                                <li>
                                    <div class="details">
                                        <a href="product.html">
                                            <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                                        </a>
                                        <div class="text">
                                            <span class="name">Product 11</span>
                                            <div>
                                                <input type="number" value="1">
                                                <span class="price">$123.4</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="bottom">
                                <span class="price">Total: $123.4</span>
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
        <div class="location">
            <a href="index.php">Home</a>
            &nbsp;>&nbsp;
            <a href="#"><?php echo $current_cat; ?></a>
        </div>
        <section>
            <h3><?php echo $current_cat; ?></h3>
            <div class="product-list">
                <?php echo $div_prod; ?>
            </div>
        </section>
    </div>

    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
</body>

</html>