<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['photo_id'])) {
    $photo_id = $_POST['photo_id'];

    $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);

    header("Location: admin.php");
    exit();
}

$stmt = $pdo->query("SELECT photos.id, photos.filename, photos.description, users.username, photos.upload_date FROM photos JOIN users ON photos.uploaded_by = users.id ORDER BY photos.upload_date DESC");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Logo</a>
            <div>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Panel</h2>
        <div class="row">
            <?php foreach ($photos as $photo): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($photo['filename']); ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($photo['username']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($photo['description']); ?></p>
                            <p class="card-text"><small class="text-muted">Uploaded on <?php echo htmlspecialchars($photo['upload_date']); ?></small></p>
                            <form method="post">
                                <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
