<?php
function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function ierg4210_cat_fetchAll() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM CATEGORIES LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    $catid = $_POST["CATID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $desc = $_POST["DESCRIPTION"];
    $inventory = $_POST["INVENTORY"];
    $default_image = "./images/_default.jpg";
    $default_thumbnail= "./images/thumbnails/_default_thumbnail.jpg";
    
    // input sanitization
    $sanitized_catid = filter_var($catid, FILTER_SANITIZE_NUMBER_INT);
    $sanitized_name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
    $sanitized_price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $sanitized_desc = filter_var($desc, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
    $sanitized_inventory = filter_var($inventory, FILTER_SANITIZE_NUMBER_INT);

    // input validation
    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;
    if (!preg_match('/^[\w\- ]+$/', $sanitized_name))
        throw new Exception("invalid-name");
    if (!preg_match('/^\d+\.?\d*$/', $sanitized_price))
        throw new Exception("invalid-price");
    $sanitized_price = (float) $sanitized_price;
    if (!preg_match('/^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/', $sanitized_desc))    
        throw new Exception("invalid-description"); 
    if (!preg_match('/^[\d]+$/', $sanitized_inventory))
        throw new Exception("invalid-inventory");
    $sanitized_inventory = (int) $sanitized_inventory;

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // first insert the record into the database
    $sql="INSERT INTO products (CATID, NAME, PRICE, DESCRIPTION, INVENTORY, IMAGE, THUMBNAIL) VALUES (?, ?, ?, ?, ?, ?, ?);";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_catid);
    $q->bindParam(2, $sanitized_name);
    $q->bindParam(3, $sanitized_price);
    $q->bindParam(4, $sanitized_desc);
    $q->bindParam(5, $sanitized_inventory);
    $q->bindParam(6, $default_image);
    $q->bindParam(7, $default_thumbnail);
    $q->execute();

    $lastId = $db->lastInsertId();

    // if there is a file being uploaded
    if ($_FILES["IMAGE"]["error"] == 0
        && ($_FILES["IMAGE"]["type"] == "image/jpeg" || $_FILES["IMAGE"]["type"] == "image/png" || $_FILES["IMAGE"]["type"] == "image/gif")
        && (mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/jpeg" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/png" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/gif")
        && $_FILES["IMAGE"]["size"] < 10000000) {
         
        // create the thumbnail
        $extension = '';
        $original = '';
        if ($_FILES["IMAGE"]["type"] == "image/jpeg") {
            $extension = '.jpg';
            $original = imagecreatefromjpeg($_FILES["IMAGE"]["tmp_name"]);
            
            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagejpeg($temp_thumb, "/var/www/html/images/thumbnails/" . $lastId . "_thumbnail.jpg")) {
                $new_thumbnail = "./images/thumbnails/" . $lastId . "_thumbnail.jpg";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $lastId);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }
        else if ($_FILES["IMAGE"]["type"] == "image/png") {
            $extension = '.png';
            $original = imagecreatefrompng($_FILES["IMAGE"]["tmp_name"]);
            
            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagepng($temp_thumb, "/var/www/html/images/thumbnails/" . $lastId . "_thumbnail.png")) {
                $new_thumbnail = "./images/thumbnails/" . $lastId . "_thumbnail.png";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $lastId);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }
        else if ($_FILES["IMAGE"]["type"] == "image/gif") {
            $original = imagecreatefromgif($_FILES["IMAGE"]["tmp_name"]);
            $extension = '.gif';

            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagegif($temp_thumb, "/var/www/html/images/thumbnails/" . $lastId . "_thumbnail.gif")) {
                $new_thumbnail = "./images/thumbnails/" . $lastId . "_thumbnail.gif";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $lastId);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }

        // update the product image once successfully uploaded and stored
        if (move_uploaded_file($_FILES["IMAGE"]["tmp_name"], "/var/www/html/images/" . $lastId . $extension)) {
            $new_image = "./images/" . $lastId . $extension;
            $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image);
            $q->bindParam(2, $lastId);
            $q->execute();
        } 

        // something went wrong
        else {
            header('Content-Type: text/html; charset=utf-8');
            echo 'Image upload failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
            exit();
        }

        // destroy the previously created copy
        imagedestroy($original);

        // redirect to the admin page
        header('Location: admin.php');
        exit();
    } 
    
    // no file is being uploaded (not error)
    else if ($_FILES["IMAGE"]["error"] == 4) {
        header('Location: admin.php');
        exit();
    }

    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

function ierg4210_cat_insert() {
    $category = $_POST['CATEGORY'];
    $sanitized_category = filter_var($category, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);

    if (!preg_match('/^[\w\- ]+$/', $sanitized_category))
        throw new Exception("invalid-category-name");

    global $db;
    $db = ierg4210_DB();

    $sql = "INSERT INTO CATEGORIES (NAME) VALUES (?);";
    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_category);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

function ierg4210_cat_update() {
    $new_category = $_POST['NEWCAT'];
    $catid = $_POST['CATID'];

    $sanitized_catid = filter_var($catid, FILTER_SANITIZE_NUMBER_INT);
    $sanitized_new_category = filter_var($new_category, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);

    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;
    if (!preg_match('/^[\w\- ]+$/', $sanitized_new_category))
        throw new Exception("invalid-category-name");

    global $db;
    $db = ierg4210_DB();

    $sql = "UPDATE CATEGORIES SET NAME=? WHERE CATID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_new_category);
    $q->bindParam(2, $sanitized_catid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

function ierg4210_cat_delete() {
    $catid = $_POST['CATID'];

    $sanitized_catid = filter_var($catid, FILTER_SANITIZE_NUMBER_INT);

    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;

    // first delete all products under the category
    ierg4210_prod_delete_by_catid($sanitized_catid);

    // then delete the category itself
    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM CATEGORIES WHERE CATID=?;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $sanitized_catid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

// to be called internally only
function ierg4210_prod_delete_by_catid($CATID) {
    $sanitized_catid = filter_var($CATID, FILTER_SANITIZE_NUMBER_INT);

    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE CATID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_catid);
    $q->execute();
}

function ierg4210_prod_fetchAll() {
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetch_by_catid($CATID) {
    $sanitized_catid = filter_var($CATID, FILTER_SANITIZE_NUMBER_INT);

    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS WHERE CATID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_catid);
    $q->execute();

    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetchOne($PID) {
    $sanitized_pid = filter_var($PID, FILTER_SANITIZE_NUMBER_INT);

    if (!preg_match('/^\d*$/', $sanitized_pid))
        throw new Exception("invalid-pid");
    $sanitized_pid = (int) $sanitized_pid;

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_pid);
    $q->execute();

    if ($q->execute())
        return $q->fetch();
}

function ierg4210_prod_update() {
    $pid = $_POST["PID"];
    $catid = $_POST["CATID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $desc = $_POST["DESCRIPTION"];
    $inventory = $_POST["INVENTORY"];
    
    // input sanitization
    $sanitized_pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
    $sanitized_catid = filter_var($catid, FILTER_SANITIZE_NUMBER_INT);
    $sanitized_name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
    $sanitized_price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $sanitized_desc = filter_var($desc, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
    $sanitized_inventory = filter_var($inventory, FILTER_SANITIZE_NUMBER_INT);

    // input validation
    if (!preg_match('/^\d*$/', $sanitized_pid))
        throw new Exception("invalid-pid");
    $sanitized_pid = (int) $sanitized_pid;
    if (!preg_match('/^\d*$/', $sanitized_catid))
        throw new Exception("invalid-catid");
    $sanitized_catid = (int) $sanitized_catid;
    if (!preg_match('/^[\w\- ]+$/', $sanitized_name))
        throw new Exception("invalid-name");
    if (!preg_match('/^\d+\.?\d*$/', $sanitized_price))
        throw new Exception("invalid-price");
    $sanitized_price = (float) $sanitized_price;
    if (!preg_match('/^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/', $sanitized_desc))    
        throw new Exception("invalid-description"); 
    if (!preg_match('/^[\d]+$/', $sanitized_inventory))
        throw new Exception("invalid-inventory");
    $sanitized_inventory = (int) $sanitized_inventory;


    global $db;
    $db = ierg4210_DB();

    // first update the record
    $sql = "UPDATE PRODUCTS SET CATID=?, NAME=?, PRICE=?, DESCRIPTION=?, INVENTORY=? WHERE PID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_catid);
    $q->bindParam(2, $sanitized_name);
    $q->bindParam(3, $sanitized_price);
    $q->bindParam(4, $sanitized_desc);
    $q->bindParam(5, $sanitized_inventory);
    $q->bindParam(6, $sanitized_pid);
    $q->execute();

    // if there is a file being uploaded
    if ($_FILES["IMAGE"]["error"] == 0
        && ($_FILES["IMAGE"]["type"] == "image/jpeg" || $_FILES["IMAGE"]["type"] == "image/png" || $_FILES["IMAGE"]["type"] == "image/gif")
        && (mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/jpeg" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/png" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/gif")
        && $_FILES["IMAGE"]["size"] < 10000000) {
         
        // create the thumbnail
        $extension = '';
        $original = '';
        if ($_FILES["IMAGE"]["type"] == "image/jpeg") {
            $extension = '.jpg';
            $original = imagecreatefromjpeg($_FILES["IMAGE"]["tmp_name"]);
            
            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagejpeg($temp_thumb, "/var/www/html/images/thumbnails/" . $sanitized_pid . "_thumbnail.jpg")) {
                $new_thumbnail = "./images/thumbnails/" . $sanitized_pid . "_thumbnail.jpg";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $sanitized_pid);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }
        else if ($_FILES["IMAGE"]["type"] == "image/png") {
            $extension = '.png';
            $original = imagecreatefrompng($_FILES["IMAGE"]["tmp_name"]);
            
            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagepng($temp_thumb, "/var/www/html/images/thumbnails/" . $sanitized_pid . "_thumbnail.png")) {
                $new_thumbnail = "./images/thumbnails/" . $sanitized_pid . "_thumbnail.png";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $sanitized_pid);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }
        else if ($_FILES["IMAGE"]["type"] == "image/gif") {
            $original = imagecreatefromgif($_FILES["IMAGE"]["tmp_name"]);
            $extension = '.gif';

            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagegif($temp_thumb, "/var/www/html/images/thumbnails/" . $sanitized_pid . "_thumbnail.gif")) {
                $new_thumbnail = "./images/thumbnails/" . $sanitized_pid . "_thumbnail.gif";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $sanitized_pid);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }

        // update the product image once successfully uploaded and stored
        if (move_uploaded_file($_FILES["IMAGE"]["tmp_name"], "/var/www/html/images/" . $sanitized_pid . $extension)) {
            $new_image = "./images/" . $sanitized_pid . $extension;
            $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image);
            $q->bindParam(2, $sanitized_pid);
            $q->execute();
        } 

        // something went wrong
        else {
            header('Content-Type: text/html; charset=utf-8');
            echo 'Image upload failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
            exit();
        }

        // destroy the previously created copy
        imagedestroy($original);

        // redirect to the admin page
        header('Location: admin.php');
        exit();
    } 
    
    // no file is being uploaded (not error)
    else if ($_FILES["IMAGE"]["error"] == 4) {
        header('Location: admin.php');
        exit();
    }

    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

function ierg4210_prod_delete() {
    $pid = $_POST["PID"];

    $sanitized_pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);

    if (!preg_match('/^\d*$/', $sanitized_pid) || !filter_var($sanitized_pid, FILTER_VALIDATE_INT))
        throw new Exception("invalid-pid");
    $sanitized_pid = (int) $sanitized_pid;

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_pid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

function ierg4210_order_fetchAll() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM ORDERS ORDER BY OID DESC LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_order_fetch_by_email($EMAIL) {
    // if ($EMAIL != 'guest') {
        $sanitized_email = filter_var($EMAIL, FILTER_SANITIZE_EMAIL);

        if (!preg_match('/^[\w._%+-]+[a-zA-Z\d]+\@{1}[\w.-]+\.[a-z]{2,8}$/', $sanitized_email) || !filter_var($sanitized_email, FILTER_VALIDATE_EMAIL))
            throw new Exception("invalid-email");    
    // } else {
    //     $sanitized_email = 'guest';
    // }

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM ORDERS WHERE USERNAME=? ORDER BY OID DESC LIMIT 5;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_email);
    $q->execute();

    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_order_fetch_by_oid($REF) {
    $sanitized_ref = filter_var($REF, FILTER_SANITIZE_STRING);
    if (!preg_match('/^[\w]+$/', $sanitized_ref))
        // throw new Exception("invalid-ref");
        return '';

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM ORDERS WHERE DIGEST=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $sanitized_ref);
    $q->execute();

    if ($q->execute())
        return $q->fetchAll();
}