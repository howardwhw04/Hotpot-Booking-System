<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
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
            <form action="account-signup-process.php" method="post">
                <table>
                    <tr>
                        <td><label for="Email">Email</label></td>
                        <td><input type="text" placeholder="Enter Email..." name="Email" required></td>
                    </tr>
                    <tr>
                        <td><label for="UserName">Username</label></td>
                        <td><input type="text" placeholder="Enter Username..." name="UserName" required></td>
                    </tr>
                    <tr>
                        <td><label for="Gender">Gender</label></td>
                        <td>
                            <select name="Gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="PhoneNumber">Phone Number</label></td>
                        <td><input type="text" placeholder="Enter Phone Number..." name="PhoneNumber" required></td>
                    </tr>
                    <tr>
                        <td><label for="psw">Password</label></td>
                        <td><input type="password" placeholder="Enter Password..." name="psw" required></td>
                    </tr>
                    <tr>
                        <td><label for="psw-repeat">Repeat Password</label></td>
                        <td><input type="password" placeholder="Repeat Password..." name="psw-repeat" required></td>
                    </tr>
                    <?php
                    // Check if 'errors' key is set before accessing it
                    if (isset($_GET['errors'])) {
                        $errorList = explode(",", $_GET['errors']);
                        foreach ($errorList as $error) {
                            echo "<b style='color:red'>$error</b>";
                        }
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td><button class="signup-button" type="submit">Sign Up</button></td>
                    </tr>  
                    
                </table>
                <input type="checkbox" checked="checked" name="agree" required>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
                <div class="to-other-page">
                    <p>Already have an account?</p>
                    <p>Press <a href="account-login.php">here</a> to login now.</p>
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
