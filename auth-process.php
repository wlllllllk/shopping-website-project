<?php
session_start();

header('Content-Type: application/json');

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {
    $db_user = ierg4210_DB_user();

	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db_user && $db_user->errorCode()) 
			error_log(print_r($db_user->errorInfo(), true));
		echo json_encode(array('failed'=>'1'));
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db_user'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}


function ierg4210_DB_user() {
	// setup the database
    $db_user = new PDO('sqlite:/var/www/user.db');

    // enable foreign key support
    $db_user->query('PRAGMA foreign_keys = ON;');

    // FETCH_ASSOC:
    // Specifies that the fetch method shall return each row as an
    // array indexed by column name as returned in the corresponding
    // result set. If the result set contains multiple columns with
    // the same name, PDO::FETCH_ASSOC returns only a single value
    // per column name.
    $db_user->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db_user;
}

// get the salt from database
//if (!preg_match('/^\d*$/', $_POST["PAGE"]))
//   throw new Exception("invalid-catid");
function ierg4210_register() {
    $db_user = ierg4210_DB_user();

    $email = strtolower($_POST["EMAIL"]);
    $password = $_POST["PASSWORD"];
    
    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);

    $result = '';
    if ($q->execute())
        $result = $q->fetch();

    if ($result != '') {
        header('Location: login.php?error=1');
        exit();
    } else {
        $name = substr($email, 0, strrpos($email,"@"));

        $salt = random_bytes(16);
        $hashed = hash_hmac('sha256', $password, $salt);
    
        $admin = 0;
    
        $sql = "INSERT INTO USERS (NAME, EMAIL, PASSWORD, SALT, ADMIN) VALUES (?, ?, ?, ?, ?);";
    
        $q = $db_user->prepare($sql);
        $q->bindParam(1, $name);
        $q->bindParam(2, $email);
        $q->bindParam(3, $hashed);
        $q->bindParam(4, $salt);
        $q->bindParam(5, $admin);
        $q->execute();
                
        header('Location: login.php?success=1');
        exit();
    }   
}

function ierg4210_login() {
    $db_user = ierg4210_DB_user();

    $email = strtolower($_POST["EMAIL"]);
    $password = $_POST["PASSWORD"];
    
    // login to existing account
    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);
        
    // account exists
    if ($q->execute()) {
        $fetched_account = $q->fetch();
    
        $salt = $fetched_account["SALT"];
        $hashed = hash_hmac('sha256', $password, $salt);
    
        if ($hashed == $fetched_account["PASSWORD"]) {
            session_regenerate_id();

            $expire = time() + 3600 * 24 * 3;
            $token = array('email'=>$email, 'expire'=>$expire, 'key'=>hash_hmac('sha256', $expire.$hashed, $salt));

            setcookie('s67', json_encode($token), $expire, '', '', true, true);

            $_SESSION['s67'] = $token;

            if ($fetched_account["ADMIN"] == 1) {
                header('Location: admin.php');
                exit();
            }
            else {
                header('Location: index.php');
                exit();
            }
        }
        else {
            header('Location: login.php?error=3');
            exit();
        }           
    }
    else {
        header('Location: login.php?error=2');
        exit();
    } 
}

function ierg4210_logout() {
    setcookie("s67", "", time() - 3600);
    $_SESSION['s67'] = "";

    session_destroy();

    header('Location: login.php?success=3');
    exit();
}

function ierg4210_change() {
    $db_user = ierg4210_DB_user();

    $current_password = $_POST["CURRENT-PASSWORD"];
    $new_password = $_POST["NEW-PASSWORD"];
    
    $email = $_SESSION['s67']['email'];

    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);
    
    if ($q->execute()) {
        $fetched_account = $q->fetch();
    
        $salt = $fetched_account["SALT"];
        $hashed = hash_hmac('sha256', $current_password, $salt);
    
        if ($hashed == $fetched_account["PASSWORD"]) {
            $new_salt = random_bytes(16);
            $new_hashed = hash_hmac('sha256', $new_password, $new_salt);

            $sql = "UPDATE USERS SET PASSWORD=?, SALT=? WHERE EMAIL=?;";

            $q = $db_user->prepare($sql);
            $q->bindParam(1, $new_hashed);
            $q->bindParam(2, $new_salt);
            $q->bindParam(3, $email);
            if ($q->execute()) {
                setcookie("s67", "", time() - 3600);
                $_SESSION['s67'] = "";
            
                session_destroy();
            
                header('Location: login.php?success=2');
                exit();
            }
        }
        else {
            header('Location: login.php?error=3');
            exit();
        }           
    }
}


?>