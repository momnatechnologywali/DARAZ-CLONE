<?php
$host = "localhost";
$dbname = "dbg544gjhtagql";
$username = "um4u5gpwc3dwc";
$password = "neqhgxo10ioe";
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
