<?php
    if (session_id() == "")
        session_start();

    include_once('./auth.php');
    $admin_button = '';
    if (auth()) {
        $admin_button = '<div><a href="./admin.php"><button>Admin Panel</button></a></div>';
    }

    include_once("./nonce.php");

    $change_password_form = '';
    $logout_button = '';

    if (empty($_SESSION['auth'])) {
        header("Location: ./login.php");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IERG4210 Member Portal</title>
    <link rel="shortcut icon" type="image/svg" href="./icon/favicon.svg">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/portal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Member Portal</h1>
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
        <div class="orders">
            <h1>Recent Orders</h1>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
                <tr>
                    <td>123</td>
                    <td>Completed</td>
                    <td>05-04-2022 19:37:25</td>
                    <td>$789.5</td>
                    <td><a href="#1">Details</a></td>
                </tr>
                
                <tr>
                    <td>123</td>
                    <td>Completed</td>
                    <td>05-04-2022 19:37:25</td>
                    <td>$789.5</td>
                    <td><a href="#2">Details</a></td>
                </tr>
                <tr>
                    <td>123</td>
                    <td>Completed</td>
                    <td>05-04-2022 19:37:25</td>
                    <td>$789.5</td>
                    <td><a href="#3">Details</a></td>
                </tr>
                </tr>
            </table>
        </div>

        <div class="second">
            <fieldset id="password-change-form">
                <legend>Change Password</legend>
                <form method="POST" action="auth-process.php?action=<?php echo ($action = 'change'); ?>"
                    onsubmit="return check_input(this);">
                    <label for="current-password">Current Password</label>
                    <input type="password" name="CURRENT-PASSWORD" id="current-password" pattern="^.+$"
                        placeholder="Enter your current password here" required="true">
                    <label for="new-password">New Password</label>
                    <input type="password" name="NEW-PASSWORD" id="new-password" pattern="^.+$"
                        placeholder="Enter your new password here" required="true">
                    <div class="actions">
                        <input type="reset" value="Reset">
                        <input type="submit" value="Submit">
                    </div>
                    <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                </form>
            </fieldset>

            <div class="buttons">
                <form method="POST" action="auth-process.php?action=<?php echo ($action = 'logout'); ?>">
                    <input class="logout" type="submit" value="Logout">
                    <input type="hidden" name="nonce" value="<?php echo csrf_getNouce($action); ?>">
                </form>

                <button id="password-change-button">Change Password</button>
            </div>

        </div>
    </div>
    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>

    <script type="text/javascript" src="../js/login.js"></script>
    <script type="text/javascript" src="../js/portal.js"></script>
</body>

</html>