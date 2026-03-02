<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practicing Day1</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-sky-700">
    <h3 class="text-white shadow-xl space-y-2 p-5 mb-10 mx-auto text-xl font-bold">Data Collection From User</h3>

    <form action="" method="POST" 
    class="max-w-md mx-auto p-6 bg-blue-400 shadow-xl rounded-lg">
        
        <label class="flex flex-1 font-medium">First Name :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none
                    focus:ring-blue-700 
                    focus:border-blue-500" type="text" name="fname" required> <br>

        <label class="flex flex-1 font-medium">Last Name :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none 
                    focus:ring-blue-700 
                    focus:border-blue-500" type="text" name="lname" required><br>

        <label class="flex flex-1 w-full font-medium">Email: &nbsp;</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none
                    focus:ring-blue-700 
                    focus:border-blue-500" type="email" name="email" required><br>

        <label class="flex flex-1 font-medium">Password :</label>
        <input class="w-full border border-blue-400 rounded-lg px-3 py-2 mb-2
                    focus:outline-none 
                    focus:ring-blue-700 
                    focus:border-blue-500" type="password" name="password" required><br>

        <button type="submit" name="sub" 
                    class="w-full mt-5 bg-blue-600 text-white py-2 rounded-lg
                    hover:bg-blue-700 transistion duration-200">
                    SUBMIT
        </button>
        
    </form>
</body>
</html>