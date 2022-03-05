<?php
require __DIR__.'/lib/db.inc.php';
$pid = $_REQUEST["pid"];
$current_prod = ierg4210_prod_fetchOne($pid);
$categories = ierg4210_cat_fetchAll();

$li_cat = '';
$current_cat = '';
foreach ($categories as $value_cat) {
    if ($value_cat["CATID"] == $current_prod["CATID"]) {
        $li_cat .= '<li class="selected"><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
        $current_cat = $value_cat["NAME"];
    } else {
        $li_cat .= '<li><a href="category.php?catid='.$value_cat["CATID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Product</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/product.css">
    <script defer src="../js/cart.js"></script>
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
                                                    <input type="number" value="">
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
            <?php echo '<a href="category.php?catid='.$current_prod["CATID"].'">'.$current_cat.'</a>' ?>
            &nbsp;>&nbsp;
            <a href="#"><?php echo $current_prod["NAME"]; ?></a>
        </div>
        <section class="product-details">
            <div class="left">
                <div class="photo"><img src="<?php echo $current_prod["IMAGE"]; ?>" alt=""></div>
                <div class="inventory">Inventory: Only <?php echo $current_prod["INVENTORY"]; ?> left!</div>
                <form action="" onsubmit="return addToCart(this)">
                        <button type="submit">Add to Cart</button>
                        <input type="text" name="PID" value=<?php echo $current_prod["PID"]; ?> readonly hidden>
                </form>
            </div>
            <div class="text">
                <div class="name"><?php echo $current_prod["NAME"]; ?></div>
                <div class="price">$<?php echo $current_prod["PRICE"]; ?></div>
                <div class="description">
                    <p>
                        <?php echo nl2br($current_prod["DESCRIPTION"]); ?>
                    </p>
                </div>
            </div>
        </section>
    </div>

    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
</body>

</html>