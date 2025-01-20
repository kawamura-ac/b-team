<?php
$host = "localhost";  // mysql309.phy.lolipop.lan
$dbname = "team_b";
<<<<<<< HEAD
$username = "LAA1618183";
$password = "dbpasswd";         

=======
$username = "LAA1617854";
$password = "dbpasswd";         


>>>>>>> 79115efad03ad0e9c03841edd455196da25cce3e
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
