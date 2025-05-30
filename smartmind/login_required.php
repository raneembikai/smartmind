<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Required</title>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #f0f9ff;
        }

        .box {
            background: rgba(255, 255, 255, 0.08);
            padding: 40px 50px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            text-align: center;
            animation: fadeIn 1s ease;
            max-width: 420px;
            width: 90%;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #e0f2fe;
        }

        p {
            font-size: 17px;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #1e40af;
            box-shadow: 0 0 18px rgba(30, 64, 175, 0.6);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Hold on! 🔐</h1>
        <p>You must be logged in to access the IQ Test.</p>
        <p>Redirecting to login in 5 seconds...</p>
        <a href="login.php" class="btn">Go to Login Now</a>
    </div>
</body>
</html>
