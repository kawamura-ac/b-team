<?php
$host = "localhost";  // mysql309.phy.lolipop.lan
$dbname = "team_b";
$username = "LAA1618183";
$password = "dbpasswd";         


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
