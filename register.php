<?php
session_start();
require 'db_config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $nickname = trim($_POST['nickname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    // Check if fields are empty
    if (empty($nickname) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        try {
            // Check if the nickname or email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = :nickname OR email = :email");
            $stmt->execute(['nickname' => $nickname, 'email' => $email]);
            $existingUser = $stmt->fetch();
            if ($existingUser) {
                $error = "Nickname or email is already taken.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Insert user into the database
                $stmt = $pdo->prepare("INSERT INTO users (nickname, email, password) VALUES (:nickname, :email, :password)");
                $result = $stmt->execute([
                    'nickname' => $nickname,
                    'email' => $email,
                    'password' => $hashedPassword
                ]);
                if ($result) {
                    // Redirect to the login page after successful registration
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "Failed to register. Please try again.";
                }
            }
        } catch (Exception $e) {
            // Log the error (optional for debugging in development)
            error_log("Error during registration: " . $e->getMessage());
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>登録</title>
</head>
<body>
    <div class="container">
        <h2>登録</h2>
        <form action="register.php" method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="nickname">ニックネーム</label>
            <input type="text" name="nickname" id="nickname" required>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" required>
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">登録</button>
        </form>
        <p>すでにアカウントをお持ちですか？ <a href="index.php">ログイン</a></p>
    </div>
</body>
</html>

?>










