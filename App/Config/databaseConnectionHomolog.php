<?php 
    $username = 'apisulamerica';
    $password = 'Kodb!89Po';

    try {
        $conn = new PDO('mysql:host=172.16.1.8;dbname=gsc;charset=utf8', $username, $password); # Homologacao
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
