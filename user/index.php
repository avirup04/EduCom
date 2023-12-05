<?php
require_once '../func.class.php';
// singel page API version 0.5
require_once $BASEDIR . '/class/user.class.php';

$host = 'localhost';
$user = 'root';
$pasw = '';
$db = 'educom';

$user = new User($host, $user, $pasw, $db);

// Create a new User or Get All Users or Get One User
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['name']) && !empty($data['phone']) && !empty($data['email']) && !empty($data['class']) && !empty($data['join_date']) && !empty($data['is_active'])) {
        $newUser = $user->createUser('users', (array)$data);
        echo json_encode($newUser);
    } elseif (isset($data['type']) && ($data['type'] === 'getAllUsers')) {
        $users = $user->getAllUsers('users');
        http_response_code(201);
        echo json_encode($users);
    } elseif (isset($data['type']) && ($data['type'] === 'getUser')) {
        if (isset($data['id']) && $data['id'] !== '') {
            $user = $user->getUser('users', $data['id']);
            if ($user !== null) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "User ID Not Found 😞"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "User ID is missing or appears to be blank 😕"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing required data. [name, phone, email, join_date] 😕"));
    }
}
// Update user information
elseif ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!empty($data)) {
        $updateUser = $user->updateUser('users', $data);
        http_response_code(201);
        echo json_encode($updateUser);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing required data. 😕"));
    }
}
// Delete User information
elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id']) && ($data['id'] !== null && $data['id'] !== "")) {
        $deleteUser = $user->deleteUser('users', $data['id']);
        http_response_code(201);
        echo json_encode($deleteUser);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "User ID is missing or appears to be blank 😕"));
    }
}
// nothing to do here
else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed. 🙁", "method" => $_SERVER['REQUEST_METHOD']));
}
