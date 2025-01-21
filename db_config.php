<?php
$host = "mysql312.phy.lolipop.lan";  // localhost
$dbname = "LAA1617854-bteam"; // 元 team_b
$username = "LAA1617854";
$password = "dbpasswd";         


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
