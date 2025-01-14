<link rel="stylesheet" href="css.php"> 
   <?php
   
    
    $body =<<<___EOF___

    <header>
        <h1>cyber</h1>
    </header>
    <h2>新規登録</h2>
    <br>
    <br>
    <center>
    <form method="POST">
        <p>ニックネーム：<input type="text" name="" required></p>
        <p>メールアドレス：<input type="email" name="email" required></p>
        <p>パスワード：<input type="password" name="password" required></p>
        <br>
        <input type="submit" value="登録">
    </form>
     </center>
     
    
    ___EOF___;
echo  $body;
?>
   