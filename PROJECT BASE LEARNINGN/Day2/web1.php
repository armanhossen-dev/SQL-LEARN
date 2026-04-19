 <!-- http://localhost/DBMS/Day2/web1.php -->
 <?php 
         //connecting to MySQL
        $con = mysqli_connect('127.0.0.1', 'root', '', 'web1');
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
        $table_web1 = "CREATE TABLE IF NOT EXISTS uinfo(
            id INT AUTO_INCREMENT PRIMARY KEY,
            fname VARCHAR(100) NOT NULL,
            lname VARCHAR(100) NOT NULL,
            uname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            pass VARCHAR(255) NOT NULL
        );";
        mysqli_query($con, $table_web1); 

        //pass length 255 because password_hash() creates long encrypted strings, it will store hashed password
        // this executes the sql query. runs this table creation command in mysql
        //$con = database connection
        //$table_web1 = sql command
        /*  PHP Script  → Create SQL query → Send query to MySQL → Table created (if not exists)  */



        //submission
        if(isset($_POST['sub'])){
            $fn = mysqli_real_escape_string($con, $_POST['fname']);
            $ln = mysqli_real_escape_string($con, $_POST['lname']);
            $un = mysqli_real_escape_string($con, $_POST['uname']);
            $em = mysqli_real_escape_string($con, $_POST['email']);
            $ps = password_hash($_POST['pass'], PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($con, "INSERT INTO uinfo (fname, lname, uname, email, pass) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $fn, $ln, $un, $em, $ps);
            
            if(mysqli_stmt_execute($stmt)){
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<p class='text-red-500 p-3 bg-pink-700 text-center'>Insert Error: " . mysqli_stmt_error($stmt) . "</p>";
            }
        } 
        //its a PRG (post-> redirect -> get) pattern
        // if the db insert query succeeds then this page reloads itslf and stops execution!
        // User submits form (POST) -> Insert into database ->  Redirect to same page (GET request) -> Page reloads cleanly 
        


        //delete request(by id)
        if(isset($_GET['delete_id'])){
            //as id is integer ,so this will not work, $e = mysqli_real_escape_string($con, $_GET['delete_id']);
            $id = (int) $_GET['delete_id'];

            $delete_sql = "DELETE FROM uinfo WHERE id = $id";
            mysqli_query($con, $delete_sql);
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit();
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practicing Day1</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-sky-300">

    <h3 class="text-white text-4xl font-bold text-center
               bg-sky-500 mb-10 mx-auto mt-5
               rounded-xl shadow-xl p-5 w-2/3">Data Collection From User</h3>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
    class="max-w-md mx-auto p-6 bg-sky-500 shadow-xl rounded-lg">
        
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
                    focus:border-blue-500" type="password" name="pass" required>

        <input type="submit" name="sub" value="Submit!"
                    class="w-full mt-5 bg-blue-600 text-white py-2 rounded-lg
                    hover:bg-blue-700 transistion duration-200">        
    </form>

<?php 
        //display all users info!
        $show = mysqli_query($con,
                                "SELECT id,
                                CONCAT(fname,' ',lname) AS Name,
                                email AS Email,
                                uname AS UserName
                                FROM uinfo");
        if(!$show){
            die(mysqli_error($con));
        }

        if(mysqli_num_rows($show) > 0){
            echo "<h3 class='text-center text-4xl font-bold text-black uppercase 
                             bg-rose-500 w-2/3 mx-auto py-5 mt-10'>All Users Info!</h3>";
            echo "<table class='mx-auto 
                                p-5 mb-2xl mx-auto text-xl text-black
                                px-3 py-2 mb-10
                                bg-white border-0 w-2/3 text-center'>";
            echo "<tr class= 'bg-rose-700 text-white'>
                    <th>Name</th>
                    <th>Email</th>
                    <th>UserName</th>
                    <th>Action</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($show)){
                echo "<tr class='border-0 
                                px-3 py-2 mb-2 
                                hover:bg-rose-500 transistion duration-200'>";
                    
                    echo "<td>".htmlspecialchars($row['Name'])."</td>";
                    echo "<td>".htmlspecialchars($row['Email'])."</td>";
                    echo "<td>".htmlspecialchars($row['UserName'])."</td>";

                    echo "<td class='bg-black text-white'>
                        <a class='delete_btn  hover:text-rose-500' 
                        href='?delete_id=".$row['id']."'
                        onclick=\"return confirm('Are You Sure?');\">
                            Delete
                        </a>
                        </td>";
                    echo  "</tr>";
            }
            echo "</table>";
        }else {
            echo "<h3 class='text-center text-4xl font-bold text-white uppercase rounded-xl
                             bg-orange-600 w-2/3 mx-auto py-5 mt-10'>No User found yet!</h3>";
        }
    
        mysqli_close($con);
    
    ?>

</body>
</html>