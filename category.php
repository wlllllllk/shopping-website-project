<?php
require __DIR__.'/lib/db.inc.php';
$catid = $_REQUEST["catid"];
$categories = ierg4210_cat_fetchAll();
$li_cat = '';
$current_cat = '';
foreach ($categories as $value_cat) {
    $li_cat .= '<li><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
    if ($value_cat["CATID"] == $catid) {
        $current_cat = $value_cat["NAME"];
    }
}

$products = ierg4210_prod_fetch_by_catid($catid);
$li_prod = '';
foreach ($products as $value_prod) {
    // $li_prod .= '<option value="'.$value_prod["PID"].'"> '.$value_prod["NAME"]' </option>';
    $li_prod .= '<h3>'.$value_prod["NAME"].'</h3>';
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
            <a href=""><?php echo $current_cat; ?></a>
        </div>
        <section>
            <h3>Products of the Day</h3>
            <?php echo $li_prod; ?>
            <div class="product-list">
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 11</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
            </div>
        </section>
        <section>
            <h3>More Products</h3>
            <div class="product-list">
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 11</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
                <div class="product">
                    <a href="product.html">
                        <div class="photo"></div>
                    </a>
                    <div class="text">
                        <a href="product.html">
                            <div class="name">Product 1</div>
                        </a>
                        <div class="price">$123.4</div>
                    </div>
                    <button>Add to Cart</button>
                </div>
            </div>
        </section>
    </div>

    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
</body>

</html>