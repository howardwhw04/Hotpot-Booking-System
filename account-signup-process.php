<?php
    // Establishing connection
    $conn = mysqli_connect("localhost", "root", "", "hbs");

    $email = $_POST['Email'];
    $userPassword = $_POST['psw'];
    $passwordRepeat = $_POST['psw-repeat'];
    $userName = $_POST['UserName']; // Added line to capture UserName
    $gender = $_POST['Gender']; // Added line to capture Gender
    $phoneNumber = $_POST['PhoneNumber']; // Added line to capture PhoneNumber

    $return = "account-signup.php?errors=";
    $url = "account-login.php";

    $error = array();
    $valid = true;

    // Checks if the password and its repetition are similar
    if ($userPassword != $passwordRepeat) {
        $error[] = "Password is not identical";
        $valid = false;
    }

    // Checks if email is in a valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Email is not in the correct format";
        $valid = false;
    }

    // Checks if email is already in use
    $sql = "SELECT * FROM userinfo WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql);
    $target = mysqli_fetch_assoc($result);

    if (!empty($target['Email'])) {
        $error[] = "Email is already in use";
        $valid = false;
    }

    // Good to go!
    if ($valid) {
        $sql = "INSERT INTO userinfo (Email, UserName, Password, Gender, PhoneNumber) VALUES ('$email', '$userName', '$userPassword', '$gender', '$phoneNumber')";
        mysqli_query($conn, $sql);
        
        header("Location: " . $url);
        mysqli_close($conn);
    } else {
        // Something's wrong here
        $errorString = implode(",", $error);
        header("Location: " . $return . $errorString);
    }
?>
