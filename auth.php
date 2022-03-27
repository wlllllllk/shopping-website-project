<?php
if (session_id() == "")
    session_start();

// check if user is admin
function auth () {
    $email = '';

    // connect to database
    $db_user = new PDO('sqlite:/var/www/user.db');
    $db_user->query('PRAGMA foreign_keys = ON;');
    $db_user->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // validate using session first if available
    if (!empty($_SESSION['auth'])) {
        $email = $_SESSION['auth']['email'];

        // fetch account details from database
        $sql = "SELECT * FROM USERS WHERE EMAIL=?;";

        $q = $db_user->prepare($sql);
        $q->bindParam(1, $email);
        $q->execute();

        $result = '';

        if ($q->execute()) {
            $result = $q->fetch();

            // return true if it is an admin account
            if ($result["ADMIN"] == 1) 
                return true;
        }
    }

    // if no session available then validate using cookie
    if (!empty($_COOKIE['auth'])) {
        $token = json_decode(stripslashes($_COOKIE['auth']), true);

        // check if the cookie has expired
        if (time() > $token['expire'])
            return false;

        // fetch account details from database
        $sql = "SELECT * FROM USERS WHERE EMAIL=?;";

        $q = $db_user->prepare($sql);

        $q->bindParam(1, $token['email']);
        $q->execute();

        $result = '';

        if ($q->execute()) {
            $result = $q->fetch();
            $result_key = hash_hmac('sha256', $token['expire'].$result['PASSWORD'], $result["SALT"]);

            if ($result_key == $token['key']) {
                // setup the session
                $_SESSION['auth'] = $token;
                $email = $token['email'];

                // return true if it is an admin account
                if ($result['ADMIN'] == 1)
                    return true;
            }
        }
    }

    // no session and no cookie = can't validate -> return false
    return false;
}
?>