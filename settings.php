<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'crowdfoo';
$charset = 'utf8mb4';


$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset='.$charset,$user,$pass, $opt);


?>