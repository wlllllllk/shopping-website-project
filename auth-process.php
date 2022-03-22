<?php
if (session_id() == "")
    session_start();

include_once("./nonce.php");
csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);

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

//if (!preg_match('/^\d*$/', $_POST["PAGE"]))
//   throw new Exception("invalid-catid");

// register a new account
function ierg4210_register() {
    if (!preg_match('/^[\w._%+-]+[a-zA-Z\d]+\@{1}[\w.-]+\.[a-z]{2,8}$/', $_POST["EMAIL"]))
        throw new Exception("invalid-email");
    if (!preg_match('/^.{6,20}$/', $_POST["PASSWORD"]))
        throw new Exception("invalid-password");

    $db_user = ierg4210_DB_user();

    $email = strtolower($_POST["EMAIL"]);
    $password = $_POST["PASSWORD"];
    
    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);

    $result = '';
    if ($q->execute())
        $result = $q->fetch();

    // account already exists
    if ($result != '') {
        header('Location: login.php?error=1');
        exit();
    } 
    
    // create a new account
    else {
        // use the part before "@" from the email address as name
        $name = substr($email, 0, strrpos($email,"@"));

        // prepare the salt
        $salt = random_bytes(16);

        // hash the user-entered password with salt
        $hashed = hash_hmac('sha256', $password, $salt);
    
        // set admin flag (0 by default)
        // indicate non-admin user
        $admin = 0;
    
        // insert the record into database
        $sql = "INSERT INTO USERS (NAME, EMAIL, PASSWORD, SALT, ADMIN) VALUES (?, ?, ?, ?, ?);";
    
        $q = $db_user->prepare($sql);
        $q->bindParam(1, $name);
        $q->bindParam(2, $email);
        $q->bindParam(3, $hashed);
        $q->bindParam(4, $salt);
        $q->bindParam(5, $admin);
        $q->execute();
                
        // redirect user to login page
        header('Location: login.php?success=1');
        exit();
    }   
}

// login to existing account
function ierg4210_login() {
    if (!preg_match('/^[\w._%+-]+[a-zA-Z\d]+\@{1}[\w.-]+\.[a-z]{2,8}$/', $_POST["EMAIL"]))
        throw new Exception("invalid-email");
    if (!preg_match('/^.{6,20}$/', $_POST["PASSWORD"]))
        throw new Exception("invalid-password");

    $db_user = ierg4210_DB_user();

    $email = strtolower($_POST["EMAIL"]);
    $password = $_POST["PASSWORD"];
    
    // try to fetch the account from database using user-entered email
    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);
        
    if ($q->execute()) {
        $fetched_account = $q->fetch();

        // account does not exist
        if ($fetched_account['UID'] == '') {

            // redirect to login page
            header('Location: login.php?error=2');
            exit();
        }
    
        // get the salt from database
        $salt = $fetched_account["SALT"];

        // hash the user-entered password with salt from the database record
        $hashed = hash_hmac('sha256', $password, $salt);
    
        // if the hash values match
        if ($hashed == $fetched_account["PASSWORD"]) {

            // re-generate a session id
            session_regenerate_id();

            // expire time
            $expire = time() + 3600 * 24 * 3;

            // token array (email, expire time, key)
            $token = array('email'=>$email, 'expire'=>$expire, 'key'=>hash_hmac('sha256', $expire.$hashed, $salt));

            // set the cookie with the above token
            // as well as the secure and httponly flags
            setcookie('auth', json_encode($token), $expire, '', '', true, true);

            // set the session
            $_SESSION['auth'] = $token;

            // redirect user to different page according to account type
            if ($fetched_account["ADMIN"] == 1) {
                header('Location: admin.php');
                exit();
            }
            else {
                header('Location: index.php');
                exit();
            }
        }

        // account credentials did not match
        else {
            header('Location: login.php?error=3');
            exit();
        }           
    }

    // something went wrong
    else {
        header('Location: login.php?error=4');
        exit();
    } 
}

// logout
function ierg4210_logout() {
    // set the expire time of cookie to 1 hour ago
    // so that it will expire immediately
    setcookie("auth", "", time() - 3600);

    // clear the session
    unset($_SESSION['auth']);

    // redirect to login page
    header('Location: login.php?success=3');
    exit();
}

// change password
function ierg4210_change() {
    if (!preg_match('/^.{6,20}$/', $_POST["CURRENT-PASSWORD"]))
        throw new Exception("invalid-current-password");
    if (!preg_match('/^.{6,20}$/', $_POST["NEW-PASSWORD"]))
        throw new Exception("invalid-new-password");

    $db_user = ierg4210_DB_user();

    // get user-entered passwords
    $current_password = $_POST["CURRENT-PASSWORD"];
    $new_password = $_POST["NEW-PASSWORD"];
    
    // get email from session
    if (!empty($_SESSION['auth'])) {
        $email = $_SESSION['auth']['email'];
    } 

    // if there is no session then get from cookie
    else if (!empty($_COOKIE['auth'])) {
        $token = json_decode(stripslashes($_COOKIE['auth']), true);

        // check if the token has expired
        if (time() > $token['expire'])
            $email = '';
        else
            $email = $token['email'];
    }

    // get the user account from database
    $sql = "SELECT * FROM USERS WHERE EMAIL=?;";
    
    $q = $db_user->prepare($sql);
    $q->bindParam(1, $email);
    
    if ($q->execute()) {
        $fetched_account = $q->fetch();
    
        // get the salt from database
        $salt = $fetched_account["SALT"];

        // hash the user-entered password with salt from the database record
        $hashed = hash_hmac('sha256', $current_password, $salt);
    
        // if the hash values match
        if ($hashed == $fetched_account["PASSWORD"]) {
            // prepare a new salt
            $new_salt = random_bytes(16);

            // hash the user-entered password with new salt
            $new_hashed = hash_hmac('sha256', $new_password, $new_salt);


            // update to record to database
            $sql = "UPDATE USERS SET PASSWORD=?, SALT=? WHERE EMAIL=?;";

            $q = $db_user->prepare($sql);
            $q->bindParam(1, $new_hashed);
            $q->bindParam(2, $new_salt);
            $q->bindParam(3, $email);
            if ($q->execute()) {
                // logout 
                // clear cookie
                setcookie("auth", "", time() - 3600);
                
                //clear session
                unset($_SESSION['auth']);
            
                // redirect to login page
                header('Location: login.php?success=2');
                exit();
            }
        }

        // password does not match
        else {
            header('Location: login.php?error=3');
            exit();
        }           
    }
}
?>