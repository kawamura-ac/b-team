<?php

    $dbserver = "localhost";
    # $dbserver = "mysql309.phy.lolipop.lan"; phpAdminにしかdbがない場合ここどうする？
    $dbname = "team_b";
    $dbuser = "LAA1617854"; // phpAdminにしかdbがない場合ここどうする？
    $dbpasswd = "dbpasswd"; // phpAdminにしかdbがない場合ここどうする？

    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];

    try {
        $pdo = new PDO('mysql:host=' . $dbserver . ';dbname='.$dbname,$dbuser, $dbpasswd, $opt );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
    ?>