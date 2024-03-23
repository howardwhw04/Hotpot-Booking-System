<?php
    // Session
    session_start();

    if(isset($_POST['logout']) && $_POST['logout'] == 'logout') {
        $url = "homepage.php";
        session_destroy();
        header("Location: " . $url);
        exit(); // Add an exit to prevent further execution
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Yummy Hotpot</title>
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
                <a href="homepage.php" class="active">Home</a>
                <a href="<?php echo isset($_SESSION['userloggedin']) ? "menu.php" : "account-login.php"; ?>">Menu</a>
                <a href="account-page.php"><?php echo isset($_SESSION['userloggedin']) ? "Logged in as " . $_SESSION['userloggedin'] : "Account"; ?></a>
                <a href="admin-page.php"  class="active" style="float: right; <?php echo isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? "" : "display: none"; ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
            </div>
        </div>
            
        <div class="content">

                <div class="content-container">
                    <div class="content-image">
                        <img src="image/Hotpot.png" alt="hotpot image" style="width: 100%; height:100%; object-fit: contain;">

                        <div class="center-text">
                            <h1>WELCOME</h1><br>
                            <a href="<?php echo isset($_SESSION['userloggedin']) ? "menu.php" : "account-login.php"; ?>">
                                <button>PLACE ORDER NOW</button>
                            </a>
                        </div> 
                    </div>
                </div>

            </div>
        </div>
            
        <footer>
             <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
        </footer>

    </body>
</html>

