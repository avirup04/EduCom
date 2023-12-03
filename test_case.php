<?php

// $host = 'localhost';
// $dbuser = 'root';
// $pasw = '';
// $db = 'educom';

// $conn = new mysqli($host, $dbuser, $pasw, $db);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $result = $conn->query("SELECT date FROM `attendance` WHERE unk_id = 'edu-660-com-jvs' AND date = '2023-12-02' LIMIT 1")->num_rows > 0;

// print $result != true ? "Access" : "Denied";

// $conn->close();




$timezone = new DateTimeZone('Asia/Kolkata');
$currentDateTime = new DateTime('now', $timezone);
echo $currentDate = $currentDateTime->format('Y-m-d').'\n\n';
echo $currentTime = $currentDateTime->format('H:i:s');