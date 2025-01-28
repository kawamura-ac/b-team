<?php
session_start();
require 'db_config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch posts with like and comment counts
$stmt = $pdo->prepare("
    SELECT posts.*, users.user_name, users.user_img, 
           (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.post_id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id) AS comment_count
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    ORDER BY posts.post_date DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();

// Fetch comments for each post
$commentsByPost = [];
$stmt = $pdo->prepare("SELECT comments.*, users.user_name FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = :post_id ORDER BY comment_date ASC");
foreach ($posts as $post) {
    $stmt->execute(['post_id' => $post['post_id']]);
    $commentsByPost[$post['post_id']] = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿一覧</title>
    <style>
        /* Updated CSS to fix button layout */
        .author-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .author-info strong {
            margin-right: 10px;
        }

        .author-actions {
            display: flex;
            gap: 10px;
        }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm('本当に削除しますか?');
        }

        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>投稿一覧</h2>
        <p>ログイン中のユーザー: <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        <div class="actions">
            <a href="create_post.php" class="button">新規投稿</a>
            <a href="authors_list.php" class="button">投稿者一覧</a>
            <a href="logout.php" class="button logout">ログアウト</a>
        </div>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <?php if (!empty($post['post_img'])): ?>
                        <img src="<?php echo htmlspecialchars($post['post_img']); ?>" alt="投稿画像" class="post-image">
                    <?php endif; ?>

                    <div class="author-info">
                        <?php
                        $profileImg = !empty($post['user_img']) ? $post['user_img'] : 'default.png';
                        ?>
                        <div>
                            <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="プロフィール画像" class="profile-picture">
                            <strong>投稿者:</strong>
                            <a href="posts_by_author.php?user_id=<?php echo $post['user_id']; ?>">
                                <?php echo htmlspecialchars($post['user_name']); ?>
                            </a>
                        </div>

                        <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                            <div class="author-actions">
                                <a href="edit_post.php?post_id=<?php echo $post['post_id']; ?>" class="button update">更新</a>
                                <a href="delete_post.php?post_id=<?php echo $post['post_id']; ?>" 
                                   class="button delete" 
                                   onclick="return confirmDelete();">削除</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                    <p><small>投稿日時: <?php echo $post['post_date']; ?></small></p>

                    <!-- Display Like Button and Count -->
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
                    $stmt->execute(['post_id' => $post['post_id'], 'user_id' => $_SESSION['user_id']]);
                    $liked = $stmt->fetch();
                    ?>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="like_post_id" value="<?php echo $post['post_id']; ?>">
                        <button type="submit" class="button like">
                            <span class="like-icon">
                                <?php echo $liked ? '👍' : '👍🏼'; ?>
                            </span>
                            Like
                        </button>
                    </form>
                    <span class="like-count"><?php echo $post['like_count']; ?> Likes</span>

                    <!-- Comment Button -->
                    <button class="button comment" onclick="toggleComments(<?php echo $post['post_id']; ?>)">
                        💬 コメント
                    </button>
                    <span class="comment-count"><?php echo $post['comment_count']; ?> Comments</span>

                    <!-- Comments Section -->
                    <div id="comments-<?php echo $post['post_id']; ?>" class="comments-section" style="display:none;">
                        <h4>コメント一覧</h4>
                        <?php if (!empty($commentsByPost[$post['post_id']])): ?>
                            <?php foreach ($commentsByPost[$post['post_id']] as $comment): ?>
                                <div class="comment">
                                    <p><strong><?php echo htmlspecialchars($comment['user_name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_content']); ?></p>
                                    <p><small><?php echo $comment['comment_date']; ?></small></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>コメントがまだありません。</p>
                        <?php endif; ?>

                        <!-- Add Comment Form -->
                        <form action="" method="POST">
                            <input type="hidden" name="comment_post_id" value="<?php echo $post['post_id']; ?>">
                            <textarea name="comment_content" rows="2" placeholder="コメントを入力..." required></textarea>
                            <button type="submit">コメントを追加</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>投稿がまだありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>