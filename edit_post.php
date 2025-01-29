<?php
session_start();
require 'db_config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch post details for the specific post to be edited
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch the post data
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute([
        'post_id' => $post_id,
        'user_id' => $_SESSION['user_id']
    ]);
    $post = $stmt->fetch();

    // If the post doesn't exist or the user is not the author, redirect
    if (!$post) {
        header('Location: main.php');
        exit();
    }

    // Handle form submission (update post details)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $post_title = trim($_POST['post_title']);
        $post_content = trim($_POST['post_content']);
        $post_image = $post['post_img'];  // Keep existing image if no new image is uploaded

        // Handle image upload
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
            // Image upload path
            $upload_dir = 'uploads/';
            $image_name = $_FILES['post_image']['name'];
            $image_tmp_name = $_FILES['post_image']['tmp_name'];
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_new_name = uniqid() . '.' . $image_ext;
            $image_path = $upload_dir . $image_new_name;

            // Move the uploaded image to the uploads directory
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $post_image = $image_path;  // Update post image path
            } else {
                $error_message = "画像のアップロードに失敗しました。";
            }
        }

        // Update the post details
        $stmt = $pdo->prepare("UPDATE posts SET post_title = :post_title, post_content = :post_content, post_img = :post_img WHERE post_id = :post_id");
        $stmt->execute([
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_img' => $post_image,
            'post_id' => $post_id
        ]);

        // Redirect after the update
        header("Location: main.php");
        exit();
    }
} else {
    // If no post_id is provided, redirect to the posts page
    header('Location: main.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿編集</title>
</head>
<body>
    <div class="container">
        <h2>投稿の編集</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="post_title">タイトル</label>
            <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post['post_title']); ?>" required>

            <label for="post_content">内容</label>
            <textarea id="post_content" name="post_content" rows="5" required><?php echo htmlspecialchars($post['post_content']); ?></textarea>

            <label for="post_image">画像（オプション）</label>
            <input type="file" id="post_image" name="post_image" accept="image/*">
            <div class="actions">
                <button type="submit">投稿を更新</button>
            </div>

           
        </form>
    </div>
</body>
</html>
