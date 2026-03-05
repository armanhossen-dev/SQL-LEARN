<?php
session_start();

/* -----------------------------
   DATABASE CONNECTION
------------------------------*/
$conn = mysqli_connect("127.0.0.1","root","","web2");

if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}

/* -----------------------------
   CREATE TABLE IF NOT EXISTS
------------------------------*/
$table = "CREATE TABLE IF NOT EXISTS user_data(
id INT AUTO_INCREMENT PRIMARY KEY,
fname VARCHAR(100) NOT NULL,
lname VARCHAR(100) NOT NULL,
uname VARCHAR(100) NOT NULL,
uemail VARCHAR(255) NOT NULL UNIQUE,
pass VARCHAR(255) NOT NULL
)";

mysqli_query($conn,$table);


/* -----------------------------
   LOGIN SYSTEM
------------------------------*/
$login_message="";

if(isset($_POST['login_submit'])){

$email = mysqli_real_escape_string($conn,$_POST['email']);
$pass  = $_POST['pass'];

$result = mysqli_query($conn,"SELECT * FROM user_data WHERE uemail='$email'");

if($result && mysqli_num_rows($result)>0){

$user = mysqli_fetch_assoc($result);

if(password_verify($pass,$user['pass'])){

$_SESSION['user_email']=$user['uemail'];
$_SESSION['user_name']=$user['uname'];

$login_message="<p class='text-green-400 text-center'>Login Successful</p>";

}else{

$login_message="<p class='text-red-400 text-center'>Incorrect Password</p>";

}

}else{

$login_message="<p class='text-red-400 text-center'>Email Not Found</p>";

}

}


/* -----------------------------
   LOGOUT
------------------------------*/
if(isset($_GET['logout'])){
session_destroy();
header("Location: ".$_SERVER['PHP_SELF']);
exit();
}


/* -----------------------------
   REGISTRATION
------------------------------*/
$register_message="";

if(isset($_POST['submit_to_register_btn'])){

$fn = mysqli_real_escape_string($conn,$_POST['fname']);
$ln = mysqli_real_escape_string($conn,$_POST['lname']);
$un = mysqli_real_escape_string($conn,$_POST['uname']);
$em = mysqli_real_escape_string($conn,$_POST['email']);

$ps = password_hash($_POST['pass'],PASSWORD_DEFAULT);

$sql="INSERT INTO user_data(fname,lname,uname,uemail,pass)
VALUES('$fn','$ln','$un','$em','$ps')";

if(mysqli_query($conn,$sql)){
$register_message="Registration Successful";
}else{
$register_message="Registration Error : ".mysqli_error($conn);
}

}


/* -----------------------------
   DELETE USER
------------------------------*/
if(isset($_GET['delete'])){

$id = intval($_GET['delete']);

mysqli_query($conn,"DELETE FROM user_data WHERE id=$id");

}


/* -----------------------------
   FETCH USERS
------------------------------*/
$users = mysqli_query($conn,"SELECT * FROM user_data");

$logged_in = isset($_SESSION['user_email']);

?>


<!DOCTYPE html>
<html>
<head>

<title>Login & Register</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-black text-white">


<!-- LOGIN MESSAGE -->
<?php echo $login_message; ?>


<!-- USER LOGGED IN -->
<?php if($logged_in): ?>

<div class="text-center mt-5">

<p class="text-green-400 text-lg">
Welcome,
<?php echo htmlspecialchars($_SESSION['user_name']); ?>
</p>

<a href="?logout=1"
class="bg-red-500 px-4 py-2 rounded-lg">
Logout
</a>

</div>

<?php endif; ?>


<h1 class="text-center text-3xl font-bold mt-8 mb-10">
Login & Registration System
</h1>


<!-- REGISTER MESSAGE -->
<?php if($register_message): ?>

<p class="text-center text-green-400">
<?php echo $register_message; ?>
</p>

<?php endif; ?>


<!-- LOGIN + REGISTER SECTION -->
<div class="flex gap-10 w-4/5 mx-auto">


<!-- LOGIN FORM -->
<div class="w-1/2">

<h2 class="text-center bg-blue-600 py-2 rounded-lg mb-4">
Login
</h2>

<form method="POST"
class="bg-white/10 border border-white/20 p-6 rounded-xl">

<label>Email</label>

<input class="w-full p-2 mb-3 rounded text-black"
type="email"
name="email"
required>


<label>Password</label>

<input class="w-full p-2 mb-3 rounded text-black"
type="password"
name="pass"
required>


<input
type="submit"
name="login_submit"
value="Login"
class="w-full bg-blue-500 py-2 rounded-lg">

</form>

</div>



<!-- REGISTER FORM -->
<div class="w-1/2">

<h2 class="text-center bg-green-600 py-2 rounded-lg mb-4">
Register
</h2>

<form method="POST"
class="bg-white/10 border border-white/20 p-6 rounded-xl">


<input
class="w-full p-2 mb-2 rounded text-black"
type="text"
name="fname"
placeholder="First Name"
required>


<input
class="w-full p-2 mb-2 rounded text-black"
type="text"
name="lname"
placeholder="Last Name"
required>


<input
class="w-full p-2 mb-2 rounded text-black"
type="text"
name="uname"
placeholder="Username"
required>


<input
class="w-full p-2 mb-2 rounded text-black"
type="email"
name="email"
placeholder="Email"
required>


<input
class="w-full p-2 mb-3 rounded text-black"
type="password"
name="pass"
placeholder="Password"
required>


<input
type="submit"
name="submit_to_register_btn"
value="Register"
class="w-full bg-green-500 py-2 rounded-lg">


</form>

</div>

</div>



<!-- USER TABLE -->

<h2 class="text-center mt-14 text-2xl">
Registered Users
</h2>


<table class="mx-auto mt-5 border border-white">

<tr class="bg-gray-800">

<th class="p-2">ID</th>
<th class="p-2">Name</th>
<th class="p-2">Email</th>
<th class="p-2">Action</th>

</tr>


<?php while($row=mysqli_fetch_assoc($users)): ?>

<tr class="text-center border border-gray-600">

<td class="p-2">
<?php echo $row['id']; ?>
</td>

<td class="p-2">
<?php echo $row['fname']." ".$row['lname']; ?>
</td>

<td class="p-2">
<?php echo $row['uemail']; ?>
</td>

<td class="p-2">

<a
href="?delete=<?php echo $row['id']; ?>"
class="bg-red-500 px-3 py-1 rounded">

Delete

</a>

</td>

</tr>

<?php endwhile; ?>


</table>


</body>
</html>