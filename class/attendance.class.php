<?php
require_once '../func.class.php';
require_once 'user.class.php';

// SELECT COUNT(*) as count FROM 'attendance' WHERE user_id = '9492239771' AND DATE(datetime) = '2023-12-02 11:55:40'
$host = 'localhost';
$dbuser = 'root';
$pasw = '';
$db = 'educom';

$userOBJ = new User($host, $dbuser, $pasw, $db);


$timezone = new DateTimeZone('Asia/Kolkata');
$currentDateTime = new DateTime('now', $timezone);
$currentDate = $currentDateTime->format('Y-m-d');
$currentTime = $currentDateTime->format('H:i:s');

function CheckUserQR($qr_url)
{
    global $userOBJ;
    global $host;
    global $dbuser;
    global $pasw;
    global $db;
    global $currentDate;
    global $currentTime;

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
                // `unk_id`, `datetime`, `qr_url`, `user_id`
                $conn = new mysqli($host, $dbuser, $pasw, $db);

                return StuAttendSuc($conn, 'attendance', ['unk_id' => $unk_id, 'qr_url' => '1', 'user_id' => '0', 'date' => $currentDate, 'time' => $currentTime]);
            } else {
                return array("message" => "User Details not mached.");
            }
        } else {
            http_response_code(404);
            return array("message" => "User not found.");
        }
    } else {
        return array("message" => "URL pattern not matched.\n");
    }
}

function StuAttendSuc($connection, string $tableName, array $data)
{

    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";

    $alreadyAttended = $connection->query("SELECT date FROM `attendance` WHERE unk_id = '" . $data['unk_id'] . "' AND date = '" . $data['date'] . "' LIMIT 1")->num_rows > 0;
    if ($alreadyAttended == null && $alreadyAttended != true) {
        $sql = "INSERT INTO `$tableName`($columns) VALUES ($values)";

        $stmt = $connection->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                return array("message" => "User has successfully attended today's class.");
            } else {
                return array("message" => "Failed to attend. Error: " . $stmt->error);
            }
        } else {
            return array("message" => "Failed to prepare statement. Error: " . $connection->error);
        }
    } else {
        return array("message" => "You already attend this class.");
    }
}


// class Att
// {
//     private $connection;

//     public function __construct($host, $user, $password, $dbname)
//     {
//         $this->connection = new mysqli($host, $user, $password, $dbname);
//         if ($this->connection->connect_error) {
//             die("Connection failed: " . $this->connection->connect_error);
//         }
//     }

//     public function StuAttendSuc(string $tableName, array $data)
//     {
//         $columns = implode(", ", array_keys($data));
//         $values = "'" . implode("', '", array_values($data)) . "'";

//         $sql = "INSERT INTO `$tableName`($columns) VALUES ($values)";

//         $stmt = $this->connection->prepare($sql);
//         if ($stmt) {
//             if ($stmt->execute()) {
//                 return array("message" => "User " . $data['user_id'] . " has successfully attended today's class.");
//             } else {
//                 return array("message" => "Failed to attend. Error: " . $stmt->error);
//             }
//         } else {
//             return array("message" => "Failed to prepare statement. Error: " . $this->connection->error);
//         }
//     }
// }
