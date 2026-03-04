<?php
session_start(); //it starts a new session for a user, and remembers him, his data in server, untill the user logout or close the browser, in that website it remebers the data of the user from page to page , this line should be top of the website coding no html code or space before it

// why we should use session?
// - keep user logged in
// - identify  user between pages
// - access user-specific data
// - secure authentication

    //lets connect to mysql
    //live: http://localhost/dbms/day3/login.php
    $conn = mysqli_connect('127.0.0.1', 'root', '', 'web2');
    if(!$conn){
        die("connection filed: " . mysqli_connect_error());
    }

    /// create the new table if its not there, 
    $table_web2 = "CREATE TABLE IF NOT EXISTS user_data(
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    fname VARCHAR(100) NOT NULL,
                    lname VARCHAR(100) NOT NULL,
                    uname VARCHAR(100) NOT NULL,
                    uemail VARCHAR(255) NOT NULL UNIQUE,
                    pass VARCHAR(255) NOT NULL
                    );";
    mysqli_query($conn, $table_web2);


    $login_message = "";
    if(isset($_POST['login_submit'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = $_POST['pass'];

        //fetch user by email
        $result = mysqli_query($conn, "SELECT * FROM user_data  WHERE uemail = '$email'");
        
        if($result && mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);  // $user here is like an array/ pointer thats ponts to the row where the email found, and it can work as a key to also show or have the value using the $user['columnName'] for the columnNames value !
            
            // mysqli_fetch_assoc() this function, grabs one row data at a time, associates the column names with their values!
            // every time you call it, it moves to the next row, if there is no more row it return null of false

            /* // user have the associative array of data found using email
            // now it gonna use the dta like a normal php array!
            if($user){
                echo "Hello, " . $user['uname']; // Hello Arman
                echo "Your email is: " . $user['uemail'];
            }else{
                echo "user data not found, using the email.";    
            }
            */

            // verify password now
            if(password_verify($pass, $user['pass'])){
                // $_SESSION -> is a server-side storage linked to one user's browser.
                //user login, then php creates a session id, that session id is saved in the browser cookies, actual data $_SESSION is stored on the server, not in the browser
                
                //so 
                //Browser -> send session id
                //Server -> loads $_SESSION data

                // user_email -> a key (name) i created 
                //$user['uemail'] -> value comming from database after login!
                $_SESSION['user_email'] = $user['uemail']; // user_email is the user email while login , $user['uemail'] value from the row find by the email
                //The session stores user data on the server, and the browser only keeps a session ID to identify the user.
                $_SESSION['user_name'] = $user['uname'];
                $login_message = "<p class= 'text-green-400 p-3 bg-blue-300'>Login Successfull! Welcome, ".$user['fname'].".</p>";
            }else{
                $login_message = "<p class='text-red p-3 bg-red-500'>User not found!</p>";
            }
        }

    }


    

?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <script src="https://cdn.tailwindcss.com" ></script>
</head>
<body class="bg-black">
    
</body>
</html>