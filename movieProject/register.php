<?php
session_start();
include 'config.php';

//will only run if the method of form is post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the username and pass, trim the username to remove space
    $username = trim($_POST['username']);
    //hash the password 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    //bind_param puts the variables in place of ? in the sql query, ss means they are string
    $stmt->bind_param("ss", $username, $password);

    //if success redirect to login, else if user already exist throw error
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Username already exists.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <p style="color:red;"><?php echo $error ?? ''; ?></p>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
