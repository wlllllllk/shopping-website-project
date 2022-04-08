<?php
    if (session_id() == "")
        session_start();

    include_once('./auth.php');
    $admin_button = '';
    if (auth()) {
        $admin_button = '<div><a href="./admin.php"><button>Admin Panel</button></a></div>';
    }

    include_once("./nonce.php");

    $login_form = '';
    $register_form = '';
    $change_password_form = '';
    $form_links = '';
    $logout_button = '';

    if (!empty($_SESSION['auth'])) {
        header("Location: ./portal.php");       
        exit();
    } 
    else {
        $login_form = '<fieldset id="login-form">
                            <legend>Login</legend>
                            <form method="POST" action="auth-process.php?action='.($action = "login").'" onsubmit="return check_input(this);">
                                <label for="login-email">Email</label>
                                <input type="email" name="EMAIL" id="login-email" pattern="^[\w._%+-]+[a-zA-Z\d]+@{1}[\w.-]+\.[a-z]{2,8}$" placeholder="Enter your email here" required="true">
                                <label for="login-password">Password</label>
                                <input type="password" name="PASSWORD" id="login-password" pattern="^.+$" placeholder="Enter your password here" required="true">
                                <div class="actions">
                                    <input type="reset" value="Reset">
                                    <input type="submit" value="Login">
                                </div>
                                <input type="hidden" name="nonce" value="'.csrf_getNouce($action).'">
                            </form>
                        </fieldset>';

        $register_form = '<fieldset id="register-form">
                            <legend>Register</legend>
                                <form method="POST" action="auth-process.php?action='.($action = "register").'" onsubmit="return check_input(this);">
                                    <label for="register-email">Email</label>
                                    <input type="email" name="EMAIL" id="register-email" pattern="^[\w._%+-]+[a-zA-Z\d]+@{1}[\w.-]+\.[a-z]{2,8}$" placeholder="Enter your email here" required="true">
                                    <label for="register-password">Password</label>
                                    <input type="password" name="PASSWORD" id="register-password" pattern="^.+$" placeholder="Enter your password here" required="true">
                                    <div class="actions">
                                        <input type="reset" value="Reset">
                                        <input type="submit" value="Register">
                                    </div>
                                    <input type="hidden" name="nonce" value="'.csrf_getNouce($action).'">
                                </form>
                        </fieldset>';

        $form_links = '<a href="#" id="login-link" onclick="show_form(\'login\')">Login to Current Account</a>
                    <a href="#" id="register-link" onclick="show_form(\'register\')">Create New Account</a>';
    }


    $message = '';
    if (isset($_REQUEST["error"])) {
        $error = $_REQUEST["error"];
        if ($error == 1) {
            $message = '<h1 class="message warning">Account Already Exist</h1>';
        }
        else if ($error == 2) {
            $message = '<h1 class="message warning">Account Doesn\'t Exist</h1>';
        }
        else if ($error == 3) {
            $message = '<h1 class="message warning">Wrong Credentials</h1>';
        }
        else if ($error == 4) {
            $message = '<h1 class="message warning">Not Authorized</h1>';
        }
    }

    if (isset($_REQUEST["success"])) {
        $success = $_REQUEST["success"];
        if ($success == 1) {
            $message = '<h1 class="message success">Accounted created successfully, you can login with your account credentials now</h1>';
        }
        else if ($success == 2) {
            $message = '<h1 class="message success">Password changed successfully, please login using the new credentials</h1>';
        }
        else if ($success == 3) {
            $message = '<h1 class="message success">You\'ve logout successfully</h1>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Login</title>
    <link rel="shortcut icon" type="image/svg" href="./icon/favicon.svg">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Login</h1>
            <div class="actions">
                <?php echo $admin_button; ?>
                <div class="account">
                    <a href="./index.php">
                        <button>Leave</button>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="main">
        <?php echo $message; ?>
        <?php echo $login_form; ?>
        <?php echo $register_form; ?>
        <?php echo $change_password_form; ?>
        <?php echo $form_links; ?>
        <?php echo $logout_button; ?>
    </div>
    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>

    <script type="text/javascript" src="../js/login.js"></script>
</body>

</html>