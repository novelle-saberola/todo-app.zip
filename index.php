<?php
session_start();
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger fade show">Invalid email or password!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name= "viewport" content= "width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {font-family: 'Inter', sans-serif;  }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100&);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            
        }

        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .card-body {
            padding: 2rem;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.12);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px; 
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        

        body.dark {
            background: linear-gradient(135deg, #1f1f1f 0%, #121212 100%);
            color: #eaeaea;
        }

        .dark .login-card {
            background: #1e1e1e;
            box-shadow: 0 20px 40px rgba(255,255,255,0.08);
        }

        .dark .card-header {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
        }

        .dark .form-control {
            background: #2c2c2c;
            color: white;
            border: 1px solid #444;
        }

        .dark a { color: #9bbcff; }
        .dark .btn-login {
            background: linear-gradient(135deg, #4e4376 0%, #2b5876 100%);
        }
        


        document.addEventListener("DOMContentLoaded", function() {
            if(localStorage.getItem("darkMode") === "enabled"){
                document.body.classList.add("dark");
            }
        });

        function toggleDarkMode(){
            document.body.classList.toggle("dark");

            if(document.body.classList.contains("dark")){
                localStorage.setItem("darkMode", "enabled");
            } else {
                localStorage.removeItem("darkMode");
            }
        }
        </script>



    </style>
</head>
<body>
    <div class="login-card">
        <div class="card-header">
            <h2 class="text-center">Login</h2>
        </div>
            <div class="card-body">
                <?php echo $message; ?>
                <form method="POST">
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <button type="submit" class="btn btn-login w-100">Login</button>
                    <p class="text-center mt-3">
                        <a href="register.php">Register</a> |
                        <a href="forgot_password.php">Forgot Password?</a>
                    </p>
                </form>
            </div>
    </div>
</body>
</html>
