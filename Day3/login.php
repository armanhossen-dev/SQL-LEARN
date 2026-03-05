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
        die("connection failed: " . mysqli_connect_error());
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
                $login_message = "<p class='text-red p-3 bg-red-500'>Incorrect password!</p>";
            }
        }else{
            $login_message = "<p class='text-red-400'>Email not found!</p>";
        }
    }


   //Handel Logout!
    if(isset($_GET['logout'])){
        // $_GET is a superglobal array that stores data sent through URL paramerters like index.php?logut=ture
        //isset() it checks whethera variable exists and is not NULL
        session_destroy();
        //session_destry() deletes all session data stored on the server, user becomes logged out.
        header("Location: " . $_SERVER['PHP_SELF']);
        //header() sends and HTTP redirect.
        //$_SERVER['PHP_SELF'] -> it returns the current page filename.
        exit();
        //prevent the rest of the script from running, because after header() redirect, php should not continue executing the remaining code
    }

    //show logged in user
    $logged_in = isset($_SESSION['user_email']);


     
    //Handel Registration
    $register_message = "";

    if(isset($_POST['submit_to_register_btn'])){
        $fn = mysqli_real_escape_string($conn, $_POST['fname']);
        $ln = mysqli_real_escape_string($conn, $_POST['lname']);
        $un = mysqli_real_escape_string($conn, $_POST['uname']);
        $em = mysqli_real_escape_string($conn, $_POST['email']);
        $ps = mysqli_real_escape_string($conn, $_POST['pass']);
        
        $insert_sql = "INSERT INTO user_data(fname, lname, uname, uemail, pass) VALUES 
        ('$fn','$ln','$un','$em','$ps')";

        if(mysqli_query($conn, $insert_sql)){
            $register_message = "Registration Successful!";
        }else{
            $register_message = "Registration Error: ". mysqli_error($conn);
        }
    }
    //close connection
    // mysqli_close($conn);

    
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
    
    <?php echo $login_message; ?>
    
        <?php if($logged_in): ?> <!-- if the user is logged in, run the code below -->
            <p class="p-3 text-red-400 text-white">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                <a href="?logout=1" class="bg-red-500 px-3 py-2 rounded-lg">Logout</a>
            </p>
        
        <?php else: ?>
            <h2 class="text-white p-3 mx-auto w-2/3 rounded-xl my-10 bg-red-600 uppercase
                      text-2xl text-center font-bold">Login System</h2>
          
            
            <?php if($register_message): ?>
                <p id="regMsg"
                class="bg-green-500/20 text-green-300 border border-green-500
                max-w-lg w-2/3 mx-auto py-3 my-4 text-center rounded-xl transition-opacity duration-500">
                    <?php echo $register_message; ?>
                </p>
            <?php endif; ?> 
        <?php endif; ?> 


            <h3 class="bg-white/10 text-white border border-white/20 max-w-lg w-2/3 mx-auto py-3 my-2 text-center rounded-xl">Register New User</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
              class="max-w-lg mx-auto
                    bg-white/10 backdrop-blur-xl border border-white/25 rounded-2xl p-8
                    hover:border-white/40 transition">                

            <label class="text-white flex flex-1" for="fname">First Name:</label>
            <input class="w-full border border-blue-800 rounded-lg 
                         py-1 px-2 mb-3 
                         focus: outline-none
                         focus:ring-blue-400
                         focus:border-blue-500"
                        type="text" name="fname" required>
            
            <label class="text-white flex flex-1" for="lname">Last Name:</label>
            <input class="w-full border border-blue-800 rounded-lg 
                         py-1 px-2 mb-3 
                         focus: outline-none
                         focus:ring-blue-400
                         focus:border-blue-500"
                        type="text" name="lname" required>
            
            <label class="text-white flex flex-1" for="email">Email :</label>
            <input class="w-full border border-blue-800 rounded-lg 
                         py-1 px-2 mb-3 
                         focus: outline-none
                         focus:ring-blue-400
                         focus:border-blue-500"
                        type="email" name="email" required>

            <label class="text-white flex flex-1" for="uname">User Name:</label>
            <input class="w-full border border-blue-800 rounded-lg 
                         py-1 px-2 mb-3 
                         focus: outline-none
                         focus:ring-blue-400
                         focus:border-blue-500"
                        type="text" name="uname" required>

            <label class="text-white flex flex-1" for="pssword">Password:</label>
            <input class="w-full border border-blue-800 rounded-lg 
                         py-1 px-2 mb-3 
                         focus: outline-none
                         focus:ring-blue-400
                         focus:border-blue-500"
                        type="password" name="pass" required>


            <input class="w-full  mt-3 bg-white  py-2 rounded-lg font-bold
                         hover:bg-yellow-500 transition duration-200"
             type="submit" name="submit_to_register_btn" value="Register">
        </form>




        <script src="login.js"></script>
    </body>
</html>