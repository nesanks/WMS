<?php

session_start();

$host = 'localhost'; // Database host

$db = 'user_db'; // Database name

$user = 'root'; // Database username

$pass = ''; // Database password


// Create connection

$conn = new mysqli($host, $user, $pass, $db);


// Check connection

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];

    $password = $_POST['password'];


    // Prepare and bind

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $stmt->store_result();


    if ($stmt->num_rows > 0) {

        $stmt->bind_result($hashed_password);

        $stmt->fetch();


        // Verify the password

        if (password_verify($password, $hashed_password)) {

            $_SESSION['username'] = $username;

            header("Location: welcome.php"); // Redirect to welcome page

            exit();

        } else {

            $error = "Invalid username or password.";

        }

    } else {

        $error = "Invalid username or password.";

    }

    $stmt->close();

}


$conn->close();

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Page</title>

</head>

<body>

    <h2>Login</h2>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <form method="post" action="">

        <label for="username">Username:</label><br>

        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>

        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">

    </form>

</body>

</html>