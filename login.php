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
</head>

<body>
    <header>
        <nav>
            <a href="./index.php" id="logo"><span>IERG4210<br>Store</span></a>
            <h1>Login</h1>
            <div class="actions">
                <div class="account">
                    <a href="./index.php">
                        <button>Leave</button>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="main">
        <fieldset id="login-form">
            <legend>Login</legend>
            <form method="POST" action="auth-process.php?action=login" onsubmit="return check_input(this);">
                <label for="login-email">Email</label>
                <input type="email" name="EMAIL" id="login-email" placeholder="Enter your email here">
                <label for="PASSWORD">Password</label>
                <input type="password" name="PASSWORD" id="login-password" placeholder="Enter your password here">
                <div class="actions">
                    <input type="submit" name="REGISTER" value="Register">
                    <input type="submit" name="LOGIN" value="Login">
                </div>
            </form>
        </fieldset>
    </div>
    <footer><span>IERG4210 Assignment &#40;Spring 2022&#41; | Created by 1155147592</span></footer>
    <script src="../js/login.js"></script>
</body>

</html>