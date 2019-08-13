<?php 
    $username = 'usr_jjunior';
    $password = 'Ejwkh01!';
    try {
        $conn = new PDO('mysql:host=10.0.1.5;dbname=gsc;charset=utf8', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
