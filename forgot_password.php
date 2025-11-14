<?php
include 'db.php';
include 'utils.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
        $update->execute([$token, $expiry, $user['id']]);

        $link = "http://localhost/todo-app/reset_password.php?token=$token";
        simulate_email($email, "Password Reset", "Click: $link");
        $message = '<div class="alert alert-success">Check <code>email_log.txt</code> for reset
link!</div>';
    } else {
        $message = '<div class="alert alert-danger">Email not found!</div>';
    }
}
?>

<!DOCTYPE html>
<html><head><title>Forgot Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100&)
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
        padding: 20px;
    }

    .card {
        border-radius: 16px;
        max-width: 420px;
        width: 100%;
        padding: 2rem;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100&);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .card-body {
        padding: 2rem;
        color: #667eea;
    }

    h3 {
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .btn-warning {
        border-radius: 12px;
        padding: 0.75rem;
        font-weight: 500;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        color: white;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    a {
        color: #7c3cbcff;
        text-decoration: none;
    }
    
    a:hover {
        text-decoration: underline;
    }

    .container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }


</style>

</head><body class="container" style="background-color: linear-gradient 35deg, #667eea 0%, #764ba2 100%;">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card">
            <div class="card-body">
                <h3>Forgot Password</h3>
                <?php echo $message; ?>
                <form method="POST">
                    <div class="mb-3"><label>  Email</label><input type="email" name="email" class="formcontrol" required></div>
                    <button type="submit" class="btn btn-warning w-100">Send Reset Link</button>
                </form>
                <p class="mt-3"><a href="index.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</div>    
</body></html>
