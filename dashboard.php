<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
include 'db.php';
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$pending = $pdo->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ? AND status = 'pending'");
$pending->execute([$user_id]);
$pending_count = $pending->fetchColumn();

$overdue = $pdo->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ? AND due_date < CURDATE() AND
status != 'completed'");
$overdue->execute([$user_id]);
$overdue_count = $overdue->fetchColumn();
?>

<!DOCTYPE html>
<html><head><title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
    * {font-family: 'Times New Roman', sans-serif;  }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .card-body {
        background: #ead2feff;
        color: #321c32ff;
        border-radius: 12px;
    }

    .card {
        border-radius: 12px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        
    }

    /* DARK MODE */
    body.dark {
        background: #121212 !important;
        color: #eaeaea !important;
    }

    body.dark .card,
    body.dark .login-card {
        background: #1e1e1e !important;
        color: #fff !important;
        box-shadow: 0 0 20px rgba(255,255,255,0.05);
    }

    body.dark .form-control {
        background: #2c2c2c;
        color: #fff;
        border: 1px solid #444;
    }

    body.dark .navbar {
        background: #1b1b1b !important;
    }

    body.dark .btn {
        border-color: #888 !important;
    }

    body.dark a { color: #9bbcff !important; }

</style>
</head><body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card text-white bg-warning"><div class="card-body"><h5>Pending Tasks</h5><h2><?php echo $pending_count; ?></h2></div></div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger"><div class="card-body"><h5>Overdue</h5><h2><?php
echo $overdue_count; ?></h2></div></div>
        </div>
    </div>
    <div class="mt-4"><a href="todos.php" class="btn btn-primary">Go to TODOs</a></div>
</div>
</body></html>
