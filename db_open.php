<?php

    $dbserver = "localhost";
    # $dbserver = "mysql309.phy.lolipop.lan";
    $dbname = "team_b";
    $dbuser = "LAA1617854";
    $dbpasswd = "dbpasswd";

    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];

    // PDO接続設定
    $dbh = new PDO('mysql:host=' . $dbserver . ';dbname='.$dbname,$dbuser, $dbpasswd, $opt );