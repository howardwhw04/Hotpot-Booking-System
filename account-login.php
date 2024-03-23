<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="Hotpot.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    </head>

    <body>
        <div>
            <header>
                <a href="homepage.php"><img class="logo" src="image/Hotpot-Logo.png" alt="Hotpot-Logo"></a>
                <a href="homepage.php"><h1>YUMMY HOTPOT</h1></a>
            </header>

            <div class="topnav">
                <a href="homepage.php">Home</a>
                <a href="<?php echo isset($_SESSION['userloggedin']) ? "menu.php" : "account-login.php"; ?>">Menu</a>
                <a href="account-page.php"  class="active"><?php echo isset($_SESSION['userloggedin']) ? "Logged in as " . $_SESSION['userloggedin'] : "Account"; ?></a>
                <a href="admin-page.php"  class="active" style="float: right; <?php echo isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? "" : "display: none"; ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
            </div>
        </div>
        

        <div class="content">
            <div class="content-container">
                <center>
                <form action="account-login-process.php" method="post">
                    <table>
                        <h1>Must Login to place order</h1>
                        <tr>
                            <td><label for="Email">Email</label></td>
                            <td><input type="text" placeholder="Enter Email..." name="Email" required></td>
                        </tr>

                        <tr>
                            <td><label for="psw">Password</label></td>
                            <td><input type="password" placeholder="Enter Password..." name="psw" required></td>
                        </tr>
                        
                        <?php
                            //Outputs any mistakes made when logging in
                            $error = isset($_GET['error']) ? $_GET['error'] : null;
                            if ($error !== null) {
                                echo "<br><b style='color:red'>The email does not exist or the password is incorrect.</b>";
                            }
                        ?>

                        <tr>
                            <td></td>
                            <td><button class="login-button" type="submit">Login</button></td>
                                
                            
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <label><input type="checkbox" checked="checked" name="remember"> Remember me</label>
                            </td>
                        </tr>
                    </table>

                    <div class="to-other-page">
                            <p>Does not have an account?</p>
                            <p>Press <a href="account-signup.php">here</a> to sign up now.</p>
                    </div>
                    
                </form>
                </center>
            </div>
        </div>

        <footer>
             <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
        </footer>
    </body>
</html>