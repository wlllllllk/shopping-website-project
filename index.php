<?php
require __DIR__.'/lib/db.inc.php';
$categories = ierg4210_cat_fetchAll();
$products = ierg4210_prod_fetchAll();

$li_cat = '';
$li_prod = '';

foreach ($categories as $value_cat) {
    $li_cat .= '<li><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
    // <li><a href="index.html"><span>Category 2</span></a></li>
}

// foreach ($categories as $value_prod) {
//     $options_prod .= '<option value="'.$value_prod["PID"].'"> '.$value_prod["NAME"]' </option>';
// }
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
<!-- 
                <li id="first"><a href="index.html"><span>Category 1</span></a></li>
                <li><a href="index.html"><span>Category 2</span></a></li>
                <li><a href="index.html"><span>Category 3</span></a></li>
                <li><a href="index.html"><span>Category 4</span></a></li>
                <li><a href="index.html"><span>Category 5</span></a></li>
                <li><a href="index.html"><span>Category 6</span></a></li>
                <li><a href="index.html"><span>Category 7</span></a></li>
                <li><a href="index.html"><span>Category 8</span></a></li>
                <li><a href="index.html"><span>Category 9</span></a></li>
                <li><a href="index.html"><span>Category 10</span></a></li>
                <li><a href="index.html"><span>Category 11</span></a></li>
                <li><a href="index.html"><span>Category 12</span></a></li>
                <li><a href="index.html"><span>Category 13</span></a></li>
                <li><a href="index.html"><span>Category 14</span></a></li>
                <li><a href="index.html"><span>Category 15</span></a></li>
                <li id="last"><a href="index.html"><span>Category 16</span></a></li> -->
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
            <h3>Products of the Day</h3>
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