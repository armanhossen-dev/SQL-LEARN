<?php
session_start();

// Connect to MySQL
$conn = mysqli_connect('127.0.0.1', 'root', '', 'web1');
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

// Handle Login Submission
$login_message = "";
if (isset($_POST['login_submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['pass'];

    // Fetch user by email
    $result = mysqli_query($conn, "SELECT * FROM u_info WHERE email='$email'");
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $login_message = "<p style='color:green;'>Login successful! Welcome, ".$user['name'].".</p>";
        } else {
            $login_message = "<p style='color:red;'>Incorrect password.</p>";
        }
    } else {
        $login_message = "<p style='color:red;'>User not found.</p>";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Optional: Show logged in user
$logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login System</title>
<style>
body { font-family: Arial, sans-serif; padding: 20px; }
form { margin-bottom: 20px; }
input[type="text"], input[type="password"] { padding: 5px; margin: 5px 0; width: 250px; }
input[type="submit"] { padding: 5px 15px; }
</style>
</head>
<body>

<h2>Login System</h2>

<?php if ($logged_in): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! <a href="?logout=1">Logout</a></p>
<?php else: ?>
    <!-- Login Form -->
    <form action="" method="POST">
        Email: <input type="text" name="email" required><br>
        Password: <input type="password" name="pass" required><br>
        <input type="submit" name="login_submit" value="Login">
    </form>
    <?php echo $login_message; ?>
<?php endif; ?>

<hr>

<h3>Register New User</h3>
<form action="" method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="text" name="reg_email" required><br>
    Password: <input type="password" name="reg_pass" required><br>
    <input type="submit" name="register_submit" value="Register">
</form>

<?php
// Handle Registration
if (isset($_POST['register_submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
    $pass = password_hash($_POST['reg_pass'], PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO u_info (name, email, password) VALUES ('$name', '$email', '$pass')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "<p style='color:green;'>Registration successful! You can now log in.</p>";
    } else {
        echo "<p style='color:red;'>Registration Error: " . mysqli_error($conn) . "</p>";
    }
}       

// Close connection
mysqli_close($conn);
?>

</body>
</html>