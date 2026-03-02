<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practicing Day1</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-400">
    <h3 class="text-white shadow-xl space-y-2 p-5 mb-10 mx-auto text-xl font-bold">Data Collection From User</h3>

    <form action="" method="POST" 
    class="max-w-md mx-auto p-6 bg-blue-500 shadow-xl rounded-lg">
        
        <label class="flex flex-1 font-medium">First Name :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none
                    focus:ring-blue-700 
                    focus:border-blue-500" type="text" name="fname" required> 

        <label class="flex flex-1 font-medium">Last Name :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none 
                    focus:ring-blue-700 
                    focus:border-blue-500" type="text" name="lname" required>

        <label class="flex flex-1 font-medium">User Name :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none
                    focus:ring-blue-700
                    focus:border-blue-500" type="text" name="uname" required>    



        <label class="flex flex-1 w-full font-medium">Email: &nbsp;</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none
                    focus:ring-blue-700 
                    focus:border-blue-500" type="email" name="email" required>

        <label class="flex flex-1 font-medium">Password :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none 
                    focus:ring-blue-700 
                    focus:border-blue-500" type="password" name="password" required>

        <button type="submit" name="sub" 
                    class="w-full mt-5 bg-blue-600 text-white py-2 rounded-lg
                    hover:bg-blue-700 transistion duration-200">
                    SUBMIT
        </button>        
    </form>



    <?php 
        //connecting to MySQL
        $con = mysqli_connect('127.0.0.1', 'root', '', 'web2');
        // $variable = mysqli_connect('127.0.0.1', 'root', 'password', 'db_name_i_want_to_connect_with');  
        //note that db should be createed before this is to be conncectd

        //checking for error 
        if(!$con){
            die("Connection failed: " . mysqli_connect_error());
            // this line stops the php program and shows an error  message if a mysql database connection fails
            //die() php function, immediately terminates (stops) the script.
            // prints a message before stopping execution, 
        }

        //create db table if it doesn't exit
        $table_web2 = "CREATE TABLE IF NOT EXISTS uinfo(
            id INT AUTO_INCREMENT PRIMARY KEY,
            fname VARCHAR(100) NOT NULL,
            lname VARCHAR(100) NOT NULL,
            uname VARCHAR(20) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            pass VARCHAR(255) NOT NULL
        );";
        mysqli_query($con, $table_web2); 

        //pass length 255 because password_hash() creates long encrypted strings, it will store hashed password
        // this executes the sql query. runs this table creation command in mysql
        //$con = database connection
        //$table_web2 = sql command
        /*  PHP Script  → Create SQL query → Send query to MySQL → Table created (if not exists)  */



        //submission
        if(isset($_POST['sub'])){
            $fn = mysqli_real_escape_string($con, $_POST['fname']);
            $ln = mysqli_real_escape_string($con, $_POST['lname']);
            $un = mysqli_real_escape_string($con, $_POST['uname']);
            $em = mysqli_real_escape_string($con, $_POST['email']);
            // $ps = mysqli_real_escape_string($con, $_POST['pass']);
            $ps = password_hash($_POST['pass'], PASSWORD_DEFAULT); //HASH PASSWORD!

            $insert_sql = "INSERT INTO uinfo (fname, lname, uname, email, pass) VALUES ('$fn', '$ln', '$un', '$em', '$ps')";
            if(mysqli_query($con, $insert_sql)){ // runs SQL query, $con -> db connection, then $insert_sql -> insert command
                header("Location: " . $_SERVER['PHP_SELF']); //this sends a redirect header to the browser, 
                // header("Location: " .... redirects user to another page
                // $_SERVER['PHP_SELF'] returns current page filename
                exit(); //Stops PHP execution immediately.

                //its a PRG (post-> redirect -> get) pattern
                // if the db insert query succeeds then this page reloads itslf and stops execution!

                // User submits form (POST) -> Insert into database ->  Redirect to same page (GET request) -> Page reloads cleanly 
            }else{
                echo "<p style = 'color:red;'>An error occurred while inserting data. Error : " . mysqli_error($con) . "</p>";
                // it displays a db error message on website in red color,if SQL query fails
                // echo -> php command used to print output to the browser.
                // mysqli_error($con) -> returns the last mysql error from the connection, shows why this query failed!
            }
        }


        //display all users info!
        $show = mysqli_query($con, "SELECT CONCAT(fname, ' ', lname) AS Name, email AS Email, uname AS UserName FROM uinfo");
        if(mysqli_num_rows($show) > 0){{
            echo "<h3>All Users Info!</h3>";
            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>UserName</th>
                    <th>Action</th>
                  </tr>";
            while ($row = mysqli_fetch_assoc($show))
            echo "</table>";
        }

        }





    ?>
</body>
</html>