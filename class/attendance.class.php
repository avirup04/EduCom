<?php
require_once '../func.class.php';
require_once 'user.class.php';


$host = 'localhost';
$user = 'root';
$pasw = '';
$db = 'educom';

$userOBJ = new User($host, $user, $pasw, $db);

function CheckUserQR($qr_url)
{
    global $userOBJ;
    // Define the pattern to match 'usrID' and 'unkID'
    $pattern = '/educom:\/\/(?<usrID>[^@]+)@(?<unkID>[^\/]+)/';

    // Perform the regular expression match
    if (preg_match($pattern, $qr_url, $matches)) {
        // Extracted values
        $usr_id = $matches['usrID'];
        $unk_id = $matches['unkID'];

        $user = $userOBJ->getUser('users', $usr_id);

        if ($user != null && isset($user[0]['unk_id'])) {
            http_response_code(200);
            if ($user[0]['unk_id'] == $unk_id) {
                return array("message" => "user exists.");
            } else {
                return array("message" => "User Details not mached.");
            }
        }else{
            http_response_code(404);
            return array("message" => "User not found.");
        }
    } else {
        return array("message" => "URL pattern not matched.\n");
    }
}
