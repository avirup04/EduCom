<?php
require_once '../func.class.php';
require_once '../class/attendance.class.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data) && !empty($data->qr_url)) {
        $usr = CheckUserQR($data->qr_url);

        echo json_encode($usr);
    }else{
        http_response_code(404);
        echo json_encode(array("message" => "Missing required parameter"));
    }
}
