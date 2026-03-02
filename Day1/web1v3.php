<?php

// Handle Delete Request (by email)
if (isset($_GET['delete_email'])) {
    $delete_email = mysqli_real_escape_string($conn, $_GET['delete_email']);
    $delete_sql = "DELETE FROM u_info WHERE email = '$delete_email'";
    mysqli_query($conn, $delete_sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBMS-WEB1</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { margin-bottom: 30px; }
        input[type="text"], input[type="password"] { padding: 5px; margin: 5px 0; width: 250px; }
        input[type="submit"] { padding: 5px 15px; }
        table { border-collapse: collapse; width: 100%; max-width: 700px; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .delete-btn { color: red; text-decoration: none; }
    </style>
</head>
<body>
<h2>Data Collect From Website to MySQL!</h2>

<!-- Input Form -->
<form action="" method="POST">
    Name     : <input type="text" name="name" required><br>
    Email    : <input type="text" name="email" required><br>
    Password : <input type="password" name="pass" required><br>
    <input type="submit" name="submit_this" value="Submit"><br>
</form>

<?php
// Display all users including password hash
$result = mysqli_query($conn, "SELECT name, email, password FROM u_info");
if (mysqli_num_rows($result) > 0) {
    echo "<h3>All Users:</h3>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Email</th><th>Password (hashed)</th><th>Action</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".$row['password']."</td>";
        echo "<td><a class='delete-btn' href='?delete_email=".$row['email']."' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found yet.</p>";
}

// Close connection
mysqli_close($conn);
?>
</body>
</html>