<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("INSERT INTO comments (photo_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$photo_id, $user_id, $comment]);

        header("Location: comment.php?photo_id=" .
        $photo_id);
        exit();
    }

    // Fetch photo details
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch comments
    $stmt = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE photo_id = ? ORDER BY comment_date DESC");
    $stmt->execute([$photo_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Logo</a>
            <div>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Comments</h2>
                <div class="card mb-4">
                    <img src="uploads/<?php echo htmlspecialchars($photo['filename']); ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($photo['description']); ?></h5>
                        <p class="card-text"><small class="text-muted">Uploaded by <?php echo htmlspecialchars($photo['uploaded_by']); ?> on <?php echo htmlspecialchars($photo['upload_date']); ?></small></p>
                    </div>
                </div>
                <form method="post" class="mb-4">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Add a comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <h4>Comments</h4>
                <?php foreach ($comments as $comment): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($comment['username']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                            <p class="card-text"><small class="text-muted">Commented on <?php echo htmlspecialchars($comment['comment_date']); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
