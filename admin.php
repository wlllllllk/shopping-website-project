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

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Admin Panel</h1>
            <div class="actions">
                <div class="account">
                    <a href="./index.php">
                        <button>Leave</button>
                    </a>                
                </div>
            </div>
        </nav>
    </header>
    <div class="main">
        <section class="left">
            <ul>
                <li class="title">Product Management</li>
                <li><a href="#product-add-form">Add Product</a></li>
                <li><a href="#product-update-form">Update Product</a></li>
                <li><a href="#product-delete-form">Delete Product</a></li>
                <li class="title">Category Management</li>
                <li><a href="#category-add-form">Add Category</a></li>
                <li><a href="#category-update-form">Update Category</a></li>
                <li><a href="#category-delete-form">Delete Category</a></li>
            </ul>
        </section>
        <section class="right">
                <fieldset id="product-add-form">
                    <legend>Product Add Form</legend>
                    <form method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
                        <label for="category-add-product">Category</label>
                        <select id="category-add-product" name="CATID">
                            <?php echo $options_cat; ?>
                        </select>
                        <label for="name-add-product">Product Name</label>
                        <input type="text" name="NAME" id="name-add-product">
                        <label for="price-add-product">Price</label>
                        <input type="number" name="PRICE" id="price-add-product">
                        <label for="description-add-product">Product Description</label>
                        <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10"></textarea>
                        <label for="image-add-product">Product Image</label>
                        <!-- <input type="file" name="IMAGE" required="true" accept="image/jpeg"/> -->
                        <div class="image-upload-field">
                            <input type="file" name="image-add-product" accept="image/jpeg"/>
                        </div>
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>

                <fieldset id="product-update-form">
                    <legend>Product Update Form</legend>
                    <form method="POST" action="admin-process.php?action=prod_update" enctype="multipart/form-data">
                        <label for="category-update-product">Category</label>
                        <select id="category-update-product" name="CATID">
                            <?php echo $options_cat; ?>
                        </select>
                        <label for="name-update-product">Product Name</label>
                        <input type="text" name="NAME" id="name-update-product" pattern="^[\w\-]+$"/>
                        <label for="price-update-product">Price</label>
                        <input type="number" name="PRICE" id="price-update-product" pattern="^\d+\.?\d*$"/>
                        <label for="description-update-product">Product Description</label>
                        <textarea name="DESCRIPTION" id="description-update-product" cols="30" rows="10"></textarea>
                        <label for="image-update-product">Product Image</label>
                        <input type="file" name="IMAGE" required="true" accept="image/jpeg"/>
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>

                <fieldset id="product-delete-form">
                    <legend>Product Delete Form</legend>
                    <form method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data">
                        <label for="delete-product">Product to be Delete</label>
                        <select name="PID" id="delete-product">
                            <?php echo $options_prod; ?>
                        </select>
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>

                <fieldset id="category-add-form">
                    <legend>Category Add Form</legend>
                    <form method="POST" action="admin-process.php?action=cat_insert" enctype="multipart/form-data">
                        <label for="new-category-add">New Category</label>
                        <input type="text" name="CATEGORY" id="new-category-add">
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>

                <fieldset id="category-update-form">
                    <legend>Category Update Form</legend>
                    <form method="POST" action="admin-process.php?action=cat_update" enctype="multipart/form-data">
                        <label for="current-category-update">Current Category</label>
                        <select name="CATID" id="current-category-update">
                            <?php echo $options_cat; ?>
                        </select>
                        <label for="new-category-update">New Category</label>
                        <input type="text" name="NEWCAT" id="new-category-update">
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>
                
                <fieldset id="category-delete-form">
                    <legend>Category Delete Form</legend>
                    <form method="POST" action="admin-process.php?action=cat_delete" enctype="multipart/form-data">
                        <label for="current-category-delete">Category to be Delete</label>
                        <select name="CATID" id="current-category-delete">
                            <?php echo $options_cat; ?>
                        </select>
                        <input type="submit" value="Submit">
                    </form>
                </fieldset>
        </section>
    </div>
    <footer><span>IERG4210 Assignment (Spring 2022) | Created by 1155147592</span></footer>
    <script src="./js/main.js"></script>
</body>
</html>
