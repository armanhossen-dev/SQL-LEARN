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
</body>
</html>