<?php
session_start();
include 'config.php';
//get the username and password from the form and check if they are correcr
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    //check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    //binds the param to the ?
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //get user from result set, its like key value , we are extracting user from result
    if ($user = $result->fetch_assoc()) {
        //compare the password with hashed password
        if (password_verify($password, $user['password'])) {
            //if successful set session vars and redirect 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: movies.php");
            exit;
        }
    }
    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p style="color:red;"><?php echo $error ?? ''; ?></p>
    <p>New user? <a href="register.php">Register</a></p>
</div>
</body>
</html>
