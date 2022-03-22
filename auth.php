<?php
if (session_id() == "")
    session_start();
    
function auth () {
    $email = '';

    $db_user = new PDO('sqlite:/var/www/user.db');
    $db_user->query('PRAGMA foreign_keys = ON;');
    $db_user->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if (!empty($_SESSION['auth'])) {
        $email = $_SESSION['auth']['email'];

        $sql = "SELECT * FROM USERS WHERE EMAIL=?;";

        $q = $db_user->prepare($sql);
        $q->bindParam(1, $email);
        $q->execute();

        $result = '';

        if ($q->execute()) {
            $result = $q->fetch();

            if ($result["ADMIN"] == 1) 
                return true;
        }
    }

    if (!empty($_COOKIE['auth'])) {
        $token = json_decode(stripslashes($_COOKIE['auth']), true);

        if (time() > $token['expire'])
            return false;

        $sql = "SELECT * FROM USERS WHERE EMAIL=?;";

        $q = $db_user->prepare($sql);

        $q->bindParam(1, $token['email']);
        $q->execute();

        $result = '';

        if ($q->execute()) {
            $result = $q->fetch();
            $result_key = hash_hmac('sha256', $token['expire'].$result['PASSWORD'], $result["SALT"]);

            if ($result_key == $token['key']) {
                $_SESSION['auth'] = $token;
                $email = $token['email'];

                if ($result['ADMIN'] == 1)
                    return true;
            }
        }
    }

    return false;
}
?>