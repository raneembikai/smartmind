<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg,,rgb(134, 19, 166) 0%,rgb(134, 19, 166) 100%);
        }
        .background-design {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,rgb(134, 19, 166) 0%,rgb(134, 19, 166) 0% 100%);
            z-index: -1;
        }
        .zigzag {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(255, 255, 255, 0.1) 0.625%,
                transparent 0.625%,
                transparent 1.25%
            );
            opacity: 0.3;
            z-index: -1;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="background-design">
        <div class="zigzag"></div>
    </div>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin_dashboard.php" method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username" name="txtUsername" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Password" name="txtPassword" required>
            </div>
            <div class="flex items-center justify-center">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login Now
                </button>
            </div>
        </form>
   
     
    </div>
</body>
</html>