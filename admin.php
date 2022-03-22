<?php
    if (session_id() == "")
        session_start();

    include_once('./auth.php');
    if (!auth()) {
        header('Location: login.php?error=4');
        exit();
    }

    include_once("./nonce.php");

    require __DIR__.'/lib/db.inc.php';
    $categories = ierg4210_cat_fetchAll();
    $products = ierg4210_prod_fetchAll();

    $options_cat = '<option selected disabled></option>';
    $options_prod = '<option selected disabled></option>';
    $divs_prod = '';

    foreach ($categories as $value_cat) {
        $options_cat .= '<option value="'.$value_cat["CATID"].'"> '.$value_cat["NAME"].' </option>';
    }

    foreach ($products as $value_prod) {
        $options_prod .= '<option value="'.$value_prod["PID"].'"> (PID: '.$value_prod["PID"].') '.$value_prod["NAME"].' </option>';
        $divs_prod .= '<div class="product">
                            <div class="product-image">
                                <img src="'.$value_prod["THUMBNAIL"].'" alt="">
                            </div>
                            <div class="product-name">'.$value_prod["NAME"].'</div>
                            <div class="product-id">PID: '.$value_prod["PID"].'</div>
                            <div class="product-id">CATID: '.$value_prod["CATID"].'</div>
                        </div>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Admin</title>
    <link rel="shortcut icon" type="image/svg" href="./icon/favicon.svg">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- <div id="loading"></div> -->
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Admin Panel</h1>
            <div class="actions">
                <div class="account">
                    <a href="./login.php">
                        <button>Account</button>
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
                    <legend>Product Add Form &#40;&#42; &#61; Required&#41;</legend>
                    <form class="form-with-image-upload" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_insert'); ?>" enctype="multipart/form-data" onsubmit="return check_form(this)">
                        <label for="category-add-product">Category &#42;</label>
                        <select id="category-add-product" name="CATID" required="true">
                            <?php echo $options_cat; ?>
                        </select>
                        <label for="name-add-product">Product Name &#42;</label>
                        <input type="text" name="NAME" id="name-add-product" pattern="^[\w\- ]+$" required="true">
                        <label for="price-add-product">Price &#42;</label>
                        <input type="number" min="0" name="PRICE" step="any" id="price-add-product" pattern="^\d+\.?\d*$" required="true">
                        <label for="inventory-add-product">Inventory &#42;</label>
                        <input type="number" min="0" name="INVENTORY" id="inventory-add-product" pattern="^[\d]+$" required="true">
                        <label for="description-add-product">Product Description &#40;No Special Characters&#41; &#42;</label>
                        <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10" required="true"></textarea>
                        <label for="image-add-product">Product Image &#40;JPG&#47;GIF&#47;PNG &lt;&#61; 10MB&#41;</label>
                        <div class="image-upload-field">
                            <div class="image-preview">
                                <img src="" alt="">
                                <div></div>
                            </div>
                            <input id="image-add-product" type="file" name="IMAGE" accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div class="actions">
                            <input type="reset" value="Reset">
                            <input type="submit" value="Submit">
                        </div>              
                        <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">      
                    </form>
                </fieldset>

                <fieldset id="product-update-form">
                    <legend>Product Update Form &#40;&#42; &#61; Required&#41;</legend>
                    <div class="form-content">
                        <div class="product-list">
                            <h4>Product List</h4>
                            <?php echo $divs_prod; ?>
                        </div>
                        <form class="form-with-image-upload form-with-product-list" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_update'); ?>" enctype="multipart/form-data" onsubmit="return check_form(this)">
                            <label for="id-update-product">Current Product &#42;</label>
                            <select id="id-update-product" name="PID" required="true">
                                <?php echo $options_prod; ?>
                            </select>
                            <label for="category-update-product">Updated Category &#42;</label>
                            <select id="category-update-product" name="CATID" required="true">
                                <?php echo $options_cat; ?>
                            </select>
                            <label for="name-update-product">Updated Product Name &#42;</label>
                            <input type="text" name="NAME" id="name-update-product" pattern="^[\w\- ]+$" required="true">
                            <label for="price-update-product">Updated Price &#42;</label>
                            <input type="number" min="0" name="PRICE" step="any" id="price-update-product" pattern="^\d+\.?\d*$" required="true">
                            <label for="inventory-update-product">Updated Inventory &#42;</label>
                            <input type="number" min="0" name="INVENTORY" id="inventory-update-product" pattern="^[\d]+$" required="true">
                            <label for="description-update-product">Updated Product Description &#40;No Special Characters&#41; &#42;</label>
                            <textarea name="DESCRIPTION" id="description-update-product" cols="30" rows="10" required="true"></textarea>
                            <label for="image-update-product">Updated Product Image &#40;JPG&#47;GIF&#47;PNG &lt;&#61; 10MB&#41;</label>
                            <div class="image-upload-field">
                                <div class="image-preview">
                                    <img src="" alt="">
                                    <div></div>
                                </div>
                                <input id="image-update-product" type="file" name="IMAGE" accept="image/jpeg, image/png, image/gif">
                            </div>                        
                            <div class="actions">
                                <input type="reset" value="Reset">
                                <input type="submit" value="Submit">
                            </div>
                            <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                        </form>
                    </div>
                </fieldset>

                <fieldset id="product-delete-form">
                    <legend>Product Delete Form &#40;&#42; &#61; Required&#41;</legend>
                    <div class="form-content">
                        <div class="product-list">
                            <h4>Product List</h4>
                            <?php echo $divs_prod; ?>
                        </div>
                        <form class="form-with-product-list" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_delete'); ?>" onsubmit="return check_form(this)">
                            <label for="delete-product">Product to be Deleted &#42;</label>
                            <select name="PID" id="delete-product" required="true">
                                <?php echo $options_prod; ?>
                            </select>
                            <div class="actions">
                                <input type="reset" value="Reset">
                                <input type="submit" value="Submit">
                            </div>
                            <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                        </form>
                    </div>
                </fieldset>

                <fieldset id="category-add-form">
                    <legend>Category Add Form &#40;&#42; &#61; Required&#41;</legend>
                    <form method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_insert'); ?>" onsubmit="return check_form(this)">
                        <label for="new-category-add">New Category &#42;</label>
                        <input type="text" name="CATEGORY" id="new-category-add" pattern="^[\w\- ]+$" required="true">
                        <div class="actions">
                            <input type="reset" value="Reset">
                            <input type="submit" value="Submit">
                        </div>
                        <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                    </form>
                </fieldset>

                <fieldset id="category-update-form">
                    <legend>Category Update Form &#40;&#42; &#61; Required&#41;</legend>
                    <form method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_update'); ?>" onsubmit="return check_form(this)">
                        <label for="current-category-update">Current Category Name&#42;</label>
                        <select name="CATID" id="current-category-update" required="true">
                            <?php echo $options_cat; ?>
                        </select>
                        <label for="new-category-update">New Category Name&#42;</label>
                        <input type="text" name="NEWCAT" id="new-category-update" pattern="^[\w\- ]+$" required="true">
                        <div class="actions">
                            <input type="reset" value="Reset">
                            <input type="submit" value="Submit">
                        </div>                    
                        <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                    </form>
                </fieldset>
                
                <fieldset id="category-delete-form">
                    <legend>Category Delete Form &#40;&#42; &#61; Required&#41;</legend>
                    <form method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_delete'); ?>" onsubmit="return check_form(this)">
                        <label for="current-category-delete">Category to be Deleted &#42;</label>
                        <select name="CATID" id="current-category-delete" required="true">
                            <?php echo $options_cat; ?>
                        </select>
                        <label>*All products under this category will be deleted as well*</label>
                        <div class="actions">
                            <input type="reset" value="Reset">
                            <input type="submit" value="Submit">
                        </div>                    
                        <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                    </form>
                </fieldset>
        </section>
    </div>
    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>

    <!-- <script src="../js/common.js"></script> -->
    <script src="./js/admin.js"></script>
</body>
</html>
