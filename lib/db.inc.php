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

    // input validation or sanitization
    if (!preg_match('/^\d*$/', $_POST['CATID']))
        throw new Exception("invalid-catid");
    $_POST['CATID'] = (int) $_POST['CATID'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['NAME']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['PRICE']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\d]+$/', $_POST['INVENTORY']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/', $_POST['DESCRIPTION']))    
        throw new Exception("invalid-description"); 

    $catid = $_POST["CATID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $desc = $_POST["DESCRIPTION"];
    $inventory = $_POST["INVENTORY"];
    $default_image = "./images/_default.jpg";
    $default_thumbnail= "./images/thumbnails/_default_thumbnail.jpg";

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // first insert the record into the database
    $sql="INSERT INTO products (CATID, NAME, PRICE, DESCRIPTION, INVENTORY, IMAGE, THUMBNAIL) VALUES (?, ?, ?, ?, ?, ?, ?);";

    $q = $db->prepare($sql);
    $q->bindParam(1, $catid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $desc);
    $q->bindParam(5, $inventory);
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
    if (!preg_match('/^[\w\- ]+$/', $_POST['CATEGORY']))
        throw new Exception("invalid-category-name");

    global $db;
    $db = ierg4210_DB();

    $sql = "INSERT INTO CATEGORIES (NAME) VALUES (?);";
    $q = $db->prepare($sql);
    $name = $_POST["CATEGORY"];
    $q->bindParam(1, $name);
    $q->execute();
    // $lastId = $db->lastInsertId();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php#category-add-form');
    exit();
}

function ierg4210_cat_update() {
    if (!preg_match('/^[\w\- ]+$/', $_POST['NEWCAT']))
        throw new Exception("invalid-category-name");
    if (!preg_match('/^\d*$/', $_POST['CATID']))
        throw new Exception("invalid-catid");
    $_POST['CATID'] = (int) $_POST['CATID'];

    global $db;
    $db = ierg4210_DB();

    $sql = "UPDATE CATEGORIES SET NAME=? WHERE CATID=?;";

    $q = $db->prepare($sql);
    $new_cat = $_POST["NEWCAT"];
    $catid = $_POST["CATID"];

    $q->bindParam(1, $new_cat);
    $q->bindParam(2, $catid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php#category-update-form');
    exit();
}

function ierg4210_cat_delete() {
    if (!preg_match('/^\d*$/', $_POST['CATID']))
        throw new Exception("invalid-catid");
    $_POST['CATID'] = (int) $_POST['CATID'];

    $catid = $_POST["CATID"];

    // first delete all products under the category
    ierg4210_prod_delete_by_catid($catid);

    // then delete the category itself
    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM CATEGORIES WHERE CATID=?;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $catid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php#category-delete-form');
    exit();
}

// to be called internally only
function ierg4210_prod_delete_by_catid($CATID) {
    if (!preg_match('/^\d*$/', $CATID))
        throw new Exception("invalid-catid");
    $CATID = (int) $CATID;

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE CATID=?;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $CATID);
    $q->execute();
}

function ierg4210_prod_fetchAll() {
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetch_by_page() {
    if (!preg_match('/^\d*$/', $_POST["PAGE"]))
        throw new Exception("invalid-catid");
    $_POST["PAGE"] = (int) $_POST["PAGE"];

    $page = $_POST["PAGE"];

    $record_per_page = 10;
    
    $start_pos = ($page - 1) * $record_per_page;

    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LIMIT ?, ?;");
    $q->bindParam(1, $start_pos);
    $q->bindParam(2, $record_per_page);

    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetch_by_catid($CATID) {
    if (!preg_match('/^\d*$/', $CATID))
        throw new Exception("invalid-catid");
    $CATID = (int) $CATID;

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS WHERE CATID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $CATID);
    $q->execute();

    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetchOne($PID) {
    if (!preg_match('/^\d*$/', $PID))
        throw new Exception("invalid-pid");
    $PID = (int) $PID;

    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $PID);
    $q->execute();

    if ($q->execute())
        return $q->fetch();
}

function ierg4210_prod_update() {

    // input validation or sanitization
    if (!preg_match('/^\d*$/', $_POST['CATID']))
        throw new Exception("invalid-catid");
    $_POST['CATID'] = (int) $_POST['CATID'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['NAME']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['PRICE']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\d]+$/', $_POST['INVENTORY']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/', $_POST['DESCRIPTION']))    
        throw new Exception("invalid-description"); 

    global $db;
    $db = ierg4210_DB();

    // first update the record
    $sql = "UPDATE PRODUCTS SET CATID=?, NAME=?, PRICE=?, DESCRIPTION=?, INVENTORY=? WHERE PID=?;";

    $q = $db->prepare($sql);
    $pid = $_POST["PID"];
    $catid = $_POST["CATID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $desc = $_POST["DESCRIPTION"];
    $inventory = $_POST["INVENTORY"];

    $q->bindParam(1, $catid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $desc);
    $q->bindParam(5, $inventory);
    $q->bindParam(6, $pid);
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
            if (imagejpeg($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.jpg")) {
                $new_thumbnail = "./images/thumbnails/" . $pid . "_thumbnail.jpg";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $pid);
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
            if (imagepng($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.png")) {
                $new_thumbnail = "./images/thumbnails/" . $pid . "_thumbnail.png";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $pid);
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
            if (imagegif($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.gif")) {
                $new_thumbnail = "./images/thumbnails/" . $pid . "_thumbnail.gif";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $pid);
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
        if (move_uploaded_file($_FILES["IMAGE"]["tmp_name"], "/var/www/html/images/" . $pid . $extension)) {
            $new_image = "./images/" . $pid . $extension;
            $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image);
            $q->bindParam(2, $pid);
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
    if (!preg_match('/^\d*$/', $_POST['PID']))
        throw new Exception("invalid-pid");
    $_POST['PID'] = (int) $_POST['PID'];

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);
    $pid = $_POST["PID"];

    $q->bindParam(1, $pid);
    $q->execute();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php#product-delete-form');
    exit();
}
