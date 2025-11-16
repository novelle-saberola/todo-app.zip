<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
include 'db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['name']))) {
    $name = trim($_POST['name']);
    $stmt = $pdo->prepare("INSERT INTO categories (name, user_id) VALUES (?, ?)");
    $stmt->execute([$name, $user_id]);
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    header("Location: categories.php");
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY name");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html><head><title>Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>
    * {
        font-family: 'Times New Roman', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: #fff;
    }

    h3 {
        color: #fff;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }

    .card, .table, .form-control {
        border-radius: 12px !important;
    }

    .table {
        background: #ead2feff;
        color: #321c32ff;
        box-shadow: 0 15px 25px rgba(0,0,0,0.1);
    }

    .table thead {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: #fff;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #d2b4f8;
    }

    .form-control {
        background: #faf4ffff;
        border: 1px solid #d6b8ff;
        color: #321c32ff;
    }

    .form-control:focus {
        border-color: #9d70ff;
        box-shadow: 0 0 5px rgba(157,112,255,0.5);
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50 0%, #00c853 100%);
        border: none;
        border-radius: 10px;
        padding: 7px 20px;
        transition: 0.3s;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.15);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%);
        border: none;
        border-radius: 10px;
    }

    table tbody tr:hover {
        background: #f3e6ffff;
        transition: 0.2s;
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
    <h3>Manage Categories</h3>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-auto"><input type="text" name="name" class="form-control" placeholder="New
category" required></div>
        <div class="col-auto"><button type="submit" class="btn btn-success">Add</button></div>
    </form>
    <table class="table table-bordered">
        <thead class="table-primary"><tr><th>#</th><th>Name</th><th>Action</th></tr></thead>
        <tbody>
            <?php foreach ($categories as $i => $cat): ?>
            <tr><td><?php echo $i+1; ?></td><td><?php echo htmlspecialchars($cat['name']); ?></td>
            <td><a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete?')">Delete</a></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body></html>
