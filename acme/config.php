<?php
$servername = "localhost";
		$username = "adminer";
		$password = "P@ssw0rd";
		$db = "ACME";
       
$pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $db, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>