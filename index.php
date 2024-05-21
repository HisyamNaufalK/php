<?php
session_start();
include('config.php');

// Fetch photos from database
$stmt = $pdo->query("SELECT photos.id, photos.filename, photos.description, users.username, photos.upload_date FROM photos JOIN users ON photos.uploaded_by = users.id ORDER BY photos.upload_date DESC");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-0I/XDQK7Q2s3RjzF8Z+ClMoPQ54xC3k/KUAUOO3s1zkxV/zDvijlZ5IbR9Q7zPeG" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.like-btn').click(function() {
            var photoId = $(this).data('photo-id');
            var likeBtn = $(this);

            $.ajax({
                url: 'like.php',
                method: 'GET',
                data: { photo_id: photoId },
                success: function(response) {
                    likeBtn.toggleClass('active');
                }
            });
        });
    });
</script>

</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Logo</a>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="admin.php" class="btn btn-primary me-2">Admin</a>
                    <?php endif; ?>
                    <a href="upload.php" class="btn btn-primary me-2">Upload</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary me-2">Login</a>
                    <a href="register.php" class="btn btn-primary">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Galeri Foto</h2>
        <div class="row">
        <?php foreach ($photos as $photo): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="uploads/<?php echo htmlspecialchars($photo['filename']); ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($photo['username']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($photo['description']); ?></p>
                <p class="card-text"><small class="text-muted">Uploaded on <?php echo htmlspecialchars($photo['upload_date']); ?></small></p>
                <div class="card-text">
                    <?php if (isset($photo['like_count'])): ?>
                        Likes: <?php echo $photo['like_count']; ?>
                    <?php endif; ?>
                    <button class="btn btn-link like-btn <?php echo isset($photo['is_liked']) && $photo['is_liked'] ? 'active' : ''; ?>" data-photo-id="<?php echo $photo['id']; ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <a href="comment.php?photo_id=<?php echo $photo['id']; ?>" class="btn btn-primary">Comment</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>



        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
