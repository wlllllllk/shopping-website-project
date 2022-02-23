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

function ierg4210_prod_fetchAll() {
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    // input validation or sanitization

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command
    // if (!preg_match('/^\d*$/', $_POST['CATID']))
    //     throw new Exception("invalid-catid");
    // $_POST['CATID'] = (int) $_POST['CATID'];
    // if (!preg_match('/^[\w\- ]+$/', $_POST['NAME']))
    //     throw new Exception("invalid-name");
    // if (!preg_match('/^[\d\.]+$/', $_POST['PRICE']))
    //     throw new Exception("invalid-price");
    // if (!preg_match('/^[\w\- ]+$/', $_POST['DESCRIPTION']))
    //     throw new Exception("invalid-text"); 

    // $sql="INSERT INTO PRODUCTS (CATID, NAME, PRICE, DESCRIPTION) VALUES (?, ?, ?, ?)";
    // $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 8000000) {

        $catid = $_POST["CATID"];
        $name = $_POST["NAME"];
        $price = $_POST["PRICE"];
        $desc = $_POST["DESCRIPTION"];
        $sql="INSERT INTO products (CATID, NAME, PRICE, DESCRIPTION) VALUES (?, ?, ?, ?);";
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $desc);
        $q->execute();
        $lastId = $db->lastInsertId();

        header('Location: admin.php');
        exit();
        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/images/" . $lastId . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

// TODO: add other functions here to make the whole application complete
function ierg4210_cat_insert() {
    global $db;
    $db = ierg4210_DB();

    $sql = "INSERT INTO CATEGORIES (NAME) VALUES (?);";
    $q = $db->prepare($sql);
    $name = $_POST["CATEGORY"];
    $q->bindParam(1, $name);
    $q->execute();
    $lastId = $db->lastInsertId();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

function ierg4210_cat_update() {
    global $db;
    $db = ierg4210_DB();

    $sql = "UPDATE CATEGORIES SET NAME=? WHERE CATID=?;";

    $q = $db->prepare($sql);
    $new_cat = $_POST["NEWCAT"];
    $catid = $_POST["CATID"];

    $q->bindParam(1, $new_cat);
    $q->bindParam(2, $catid);

    $q->execute();
    $lastId = $db->lastInsertId();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}

function ierg4210_cat_delete() {
    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM CATEGORIES WHERE CATID=?;";

    $q = $db->prepare($sql);
    $catid = $_POST["CATID"];

    $q->bindParam(1, $catid);

    $q->execute();
    $lastId = $db->lastInsertId();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}


function ierg4210_prod_delete_by_catid() {

}



function ierg4210_prod_fetchOne() {

}

function ierg4210_prod_update() {

}

function ierg4210_prod_delete() {
    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);
    $catid = $_POST["PID"];

    $q->bindParam(1, $catid);

    $q->execute();
    $lastId = $db->lastInsertId();

    // redirect back to original page; you may comment it during debug
    header('Location: admin.php');
    exit();
}
