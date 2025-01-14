
   <link rel="stylesheet" href="css.php"> 
   <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $POST_['email']
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
    
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = :nickname");
        $stmt->execute(['nickname' => $nickname]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nickname'] = $user['nickname'];
            header('Location: main.php');
            exit();
        } else {
            $error = "Invalid nickname or password.";
        }
    }
   
   
    $body =<<<___EOF___

    <header>
        <h1>cyber</h1>
    </header>
    <h2>ログイン</h2>
    <br>
    <br>
    <center>
    <form method="POST" action="main.php">
        <p>ニックネーム：<input type="text" name="nickname" required></p>
        <p>メールアドレス：<input type="email" name="email" required></p>
        <p>パスワード：<input type="password" name="password" required></p>
        <br>
        <input type="submit" value="ログイン">
     </form>
     </center>
     <div class="divider"></div>
     <footer>
        <form action="Registration.php" method="GET">
            <button type="submit">新規登録はこちらから</button>
        </form>
     </footer>  
    
    ___EOF___;

echo  $body   
  
    
    
?>
   
     


