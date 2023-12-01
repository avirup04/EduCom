<?php
require_once '../func.class.php';
require_once '../class/mail.class.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data) && !empty($data->type) && !empty($data->data)) {
        if ($data->type === "userMail") {
            if (!empty($data->data->name) && !empty($data->data->usr_id) && !empty($data->data->qr_url) && !empty($data->data->email)) {
                $mailRes = SendToUser($data->data->name, $data->data->email, $data->data->qr_url, $data->data->usr_id);
                http_response_code(200);
                echo json_encode($mailRes);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'missing required data'));
            }
        }
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'data not found!'));
    }
}
