<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];
    $user_id = $_SESSION['user_id'];

    // Check if user already liked the photo
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE photo_id = ? AND user_id = ?");
    $stmt->execute([$photo_id, $user_id]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$like) {
        // Add like
        $stmt = $pdo->prepare("INSERT INTO likes (photo_id, user_id) VALUES (?, ?)");
        $stmt->execute([$photo_id, $user_id]);
    } else {
        // Remove like
        $stmt = $pdo->prepare("DELETE FROM likes WHERE photo_id = ? AND user_id = ?");
        $stmt->execute([$photo_id, $user_id]);
    }

    header("Location: index.php");
    exit();
}
?>
