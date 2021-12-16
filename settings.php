<?php

$host = 'us-cdbr-east-05.cleardb.net';
$user = 'b3b34371ab6c20';
$pass = 'fdbaec50 ';
$dbname = 'heroku_33e2be097e95825';
$charset = 'utf8mb4';


$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset='.$charset,$user,$pass, $opt);


?>