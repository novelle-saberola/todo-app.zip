<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
include 'db.php';
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $profile_pic = $user['profile_pic'];

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file = $_FILES['profile_pic'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png']) && $file['size'] < 2000000) {
            $new_name = "uploads/" . time() . "_" . $file['name'];
            if (move_uploaded_file($file['tmp_name'], $new_name)) {
                $profile_pic = $new_name;
            }
        }
    }

    $update = $pdo->prepare("UPDATE users SET bio = ?, profile_pic = ? WHERE id = ?");
    $update->execute([$bio, $profile_pic, $user_id]);
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html><head><title>Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head><body>
    <style>
    * {
        font-family: 'Times New Roman', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #acb7ebff 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .card {
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        overflow: hidden;
        background: #ead2feff;
        color: #321c32ff;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: bold;
        border-bottom: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    
    .img-fluid {
        border: 5px solid white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    h4 {
        color: #ffffff;
        text-shadow: 0 1px 4px rgba(0,0,0,0.3);
    }


    .card-body p,
    textarea.form-control {
        background: #f7eaff;
        border-radius: 10px;
        padding: 10px;
    }

    textarea.form-control {
        resize: none;
        border: 1px solid #d8b5ff;
    }

    input.form-control {
        border: 1px solid #d8b5ff;
        background: #f7eaff;
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


<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?php echo $user['profile_pic']; ?>" class="img-fluid rounded-circle"
width="180" alt="Profile">
            <h4 class="mt-3"><?php echo htmlspecialchars($user['username']); ?></h4>
        </div>
    <div class="col-md-8">
        <div class="card"><div class="card-header"><h5>About Me</h5></div>
        <div class="card-body"><p><?php echo nl2br(htmlspecialchars($user['bio'] ?: 'No bio.'));
?></p></div></div>
        <div class="card mt-4"><div class="card-header"><h5>Update Profile</h5></div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label>Bio</label><textarea name="bio" class="form-control"
rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea></div>
                <div class="mb-3"><label>Profile Picture (JPG/PNG, <2MB)</label><input
type="file" name="profile_pic" class="form-control" accept="image/*"></div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div></div>
    </div>
 </div>
</div>
</body></html>