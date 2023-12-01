<?php

ini_set('display_errors', 1);
// Allow requests from specific origin
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

// Allow the following methods
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

// Allow the following headers
header('Access-Control-Allow-Headers: Content-Type');

// Check if it's a preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respond with a 200 OK status for preflight requests
    http_response_code(200);
    exit;
}


$devDIR = '/Applications/XAMPP/xamppfiles/htdocs/educom';
$devDIRWin = 'D:\XAMPP\htdocs\EduCom';
$prodDIR = __DIR__;

$BASEDIR = $devDIRWin;
