<?php
require __DIR__.'/lib/db.inc.php';
$categories = ierg4210_cat_fetchAll();
$products = ierg4210_prod_fetchAll();

$options_cat = '';
$options_prod = '';

foreach ($categories as $value_cat) {
    $options_cat .= '<option value="'.$value_cat["CATID"].'"> '.$value_cat["NAME"].' </option>';
}

foreach ($products as $value_prod) {
    $options_prod .= '<option value="'.$value_prod["PID"].'"> '.$value_prod["NAME"].' </option>';
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
</head>

<body>
    <header>
        <nav>
            <a href="index.html" id="logo"><span>IERG4210<br>Store</span></a>
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
                    <a href="./admin.html">
                        <button>Admin</button>
                    </a>                
                </div>
            </div>
        </nav>
        <div class="categories">
            <h3>Categories</h3>
            <ul>
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
                <li id="last"><a href="index.html"><span>Category 16</span></a></li>
            </ul>
            <div class="selector">
                <a href="#first">&lt;</a>
                <a href="#last">&gt;</a>
            </div>
        </div>
    </header>

    <div class="main">
        <div class="location">
            <a href="index.html">Home</a>
            &nbsp;>&nbsp;
            <a href="index.html">Category 1</a>
            &nbsp;>&nbsp;
            <a href="product.html">Product 1</a>
        </div>
        <section class="product-details">
            <div class="left">
                <div class="photo"><img src="./images/product.jpg" alt="umbrella"></div>
                <div class="inventory">Inventory: Only 10 left!</div>
                <button>Add to Cart</button>
            </div>
            <div class="text">
                <div class="name">Product 1</div>
                <div class="price">$123.4</div>
                <div class="description">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Aliquam purus sit amet luctus venenatis lectus magna
                        fringilla. Tristique magna sit amet purus gravida. Placerat vestibulum lectus mauris
                        ultrices. Suspendisse sed nisi lacus sed viverra tellus. Leo vel fringilla est ullamcorper
                        eget nulla facilisi. Dapibus ultrices in iaculis nunc sed augue lacus viverra vitae. Viverra
                        tellus in hac habitasse platea dictumst. Nam at lectus urna duis convallis. Dui faucibus in
                        ornare quam viverra. Volutpat consequat mauris nunc congue nisi vitae suscipit tellus
                        mauris. Id aliquet lectus proin nibh nisl condimentum. Semper viverra nam libero justo
                        laoreet sit amet cursus sit. Et molestie ac feugiat sed lectus vestibulum. Aliquam sem et
                        tortor consequat. Vitae turpis massa sed elementum tempus egestas sed. Mi sit amet mauris
                        commodo quis imperdiet massa tincidunt. At urna condimentum mattis pellentesque id.
                        <br><br>
                        Eu turpis egestas pretium aenean pharetra magna ac placerat vestibulum. Pretium fusce id
                        velit ut tortor pretium viverra. Eget nunc scelerisque viverra mauris. Vitae justo eget
                        magna fermentum iaculis eu. Sit amet dictum sit amet. Gravida rutrum quisque non tellus orci
                        ac auctor augue. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Et
                        tortor consequat id porta. Dolor magna eget est lorem ipsum dolor sit. In mollis nunc sed id
                        semper risus in. In fermentum posuere urna nec tincidunt praesent. Nulla facilisi etiam
                        dignissim diam. Proin libero nunc consequat interdum varius. Quis auctor elit sed vulputate
                        mi sit amet mauris commodo. Nunc mattis enim ut tellus elementum sagittis. Consectetur lorem
                        donec massa sapien faucibus et molestie ac. Nisl pretium fusce id velit ut.
                        <br><br>
                        Non tellus orci ac auctor augue mauris augue. At quis risus sed vulputate odio ut. Netus et
                        malesuada fames ac turpis egestas sed. Eu volutpat odio facilisis mauris. Placerat
                        vestibulum lectus mauris ultrices eros in cursus turpis massa. Venenatis urna cursus eget
                        nunc scelerisque viverra mauris in. Ridiculus mus mauris vitae ultricies leo integer
                        malesuada. In massa tempor nec feugiat nisl pretium. Dapibus ultrices in iaculis nunc sed
                        augue lacus viverra vitae. Auctor neque vitae tempus quam pellentesque nec. Porta lorem
                        mollis aliquam ut. Amet aliquam id diam maecenas ultricies mi. Fusce ut placerat orci nulla.
                        Lectus urna duis convallis convallis. Semper eget duis at tellus at. Egestas purus viverra
                        accumsan in nisl nisi scelerisque.
                    </p>
                </div>
            </div>
        </section>
        <section>
            <h3>More on the product</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua. Aliquam purus sit amet luctus venenatis lectus magna
                fringilla. Tristique magna sit amet purus gravida. Placerat vestibulum lectus mauris
                ultrices. Suspendisse sed nisi lacus sed viverra tellus. Leo vel fringilla est ullamcorper
                eget nulla facilisi. Dapibus ultrices in iaculis nunc sed augue lacus viverra vitae. Viverra
                tellus in hac habitasse platea dictumst. Nam at lectus urna duis convallis. Dui faucibus in
                ornare quam viverra. Volutpat consequat mauris nunc congue nisi vitae suscipit tellus
                mauris. Id aliquet lectus proin nibh nisl condimentum. Semper viverra nam libero justo
                laoreet sit amet cursus sit. Et molestie ac feugiat sed lectus vestibulum. Aliquam sem et
                tortor consequat. Vitae turpis massa sed elementum tempus egestas sed. Mi sit amet mauris
                commodo quis imperdiet massa tincidunt. At urna condimentum mattis pellentesque id.
                <br><br>
                Eu turpis egestas pretium aenean pharetra magna ac placerat vestibulum. Pretium fusce id
                velit ut tortor pretium viverra. Eget nunc scelerisque viverra mauris. Vitae justo eget
                magna fermentum iaculis eu. Sit amet dictum sit amet. Gravida rutrum quisque non tellus orci
                ac auctor augue. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Et
                tortor consequat id porta. Dolor magna eget est lorem ipsum dolor sit. In mollis nunc sed id
                semper risus in. In fermentum posuere urna nec tincidunt praesent. Nulla facilisi etiam
                dignissim diam. Proin libero nunc consequat interdum varius. Quis auctor elit sed vulputate
                mi sit amet mauris commodo. Nunc mattis enim ut tellus elementum sagittis. Consectetur lorem
                donec massa sapien faucibus et molestie ac. Nisl pretium fusce id velit ut.
                <br><br>
                Non tellus orci ac auctor augue mauris augue. At quis risus sed vulputate odio ut. Netus et
                malesuada fames ac turpis egestas sed. Eu volutpat odio facilisis mauris. Placerat
                vestibulum lectus mauris ultrices eros in cursus turpis massa. Venenatis urna cursus eget
                nunc scelerisque viverra mauris in. Ridiculus mus mauris vitae ultricies leo integer
                malesuada. In massa tempor nec feugiat nisl pretium. Dapibus ultrices in iaculis nunc sed
                augue lacus viverra vitae. Auctor neque vitae tempus quam pellentesque nec. Porta lorem
                mollis aliquam ut. Amet aliquam id diam maecenas ultricies mi. Fusce ut placerat orci nulla.
                Lectus urna duis convallis convallis. Semper eget duis at tellus at. Egestas purus viverra
                accumsan in nisl nisi scelerisque.
            </p>
        </section>
    </div>

    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
</body>

</html>