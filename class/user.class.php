<?php

class User
{
    private $connection;

    public function __construct($host, $user, $password, $db)
    {
        $this->connection = new mysqli($host, $user, $password, $db);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function createUser($tableName, $data)
    {
        // Convert data array to comma-separated strings
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        // Check if phone and email are unique
        $checkUserData = json_decode($this->checkUser($data['phone'], $data['email']));
        
        // If both phone and email are unique, insert the new user
        if (!$checkUserData->isPhone && !$checkUserData->isEmail) {
            $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
            if ($this->connection->query($sql)) {
                return array('message' => 'User created successfully 😊');
            } else {
                return array('message' => 'Faild to creat User 😞');
            }
        } else {
            $msg = "";
            $msg .= $checkUserData->isPhone === true ? " Phone already exists 😕. " : " ";
            $msg .= $checkUserData->isEmail === true ? " Email already exists 😕. " : " ";
            return array("message" => $msg);
        }
    }


    public function getUser($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $this->connection->query($sql);
        return $result->fetch_assoc();
    }

    public function updateUser($tableName, $data)
    {
        $updatePairs = [];
        foreach ($data as $column => $value) {
            $updatePairs[] = "$column = '$value'";
        }
        $updateColumns = implode(", ", $updatePairs);
        // Assuming there's an 'id' column in the data
        $id = $data['id'];
        $sql = "SELECT * FROM $tableName WHERE id = $id";
        $result = $this->connection->query($sql);
        if ($result->num_rows > 0) {
            $sql = "UPDATE $tableName SET $updateColumns WHERE id = $id";

            if ($this->connection->query($sql)) {
                http_response_code(201);
                return array("message" => "Deatils updated Successfully 😊");
            } else {
                http_response_code(400);
                return array("message" => "Failed to Update Deatils 😕");
            }
        } else {
            return array("message" => "User ID Not Found 😞");
        }
    }

    public function deleteUser($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $this->connection->query($sql);
        if ($result->num_rows > 0) {
            $sql = "DELETE FROM $table WHERE id = $id";
            if ($this->connection->query($sql)) {
                return array("message" => "User deleted");
            }
        } else {
            return array("message" => "User ID Not Found 😞");
        }
    }

    public function getAllUsers($table)
    {
        $sql = "SELECT * FROM $table";
        $result = $this->connection->query($sql);
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }


    public function checkUser($phone, $email)
    {
        // Check if phone and email exist in the database
        $isPhone = $this->connection->query("SELECT * FROM users WHERE phone = '$phone'")->num_rows > 0;
        $isEmail = $this->connection->query("SELECT * FROM users WHERE email = '$email'")->num_rows > 0;

        // Return JSON response indicating phone and email status
        return json_encode(array('isPhone' => $isPhone, 'isEmail' => $isEmail));
    }
    public function checkUnkIDUsrID($unkid, $usrid)
    {
        // Check if phone and email exist in the database
        $isUnkid = $this->connection->query("SELECT * FROM users WHERE unkid = '$unkid'")->num_rows > 0;
        $isEmail = $this->connection->query("SELECT * FROM users WHERE usrid = '$usrid'")->num_rows > 0;

        // Return JSON response indicating phone and email status
        return json_encode(array('isUnkid' => $isUnkid, 'isUsrid' => $isEmail));
    }


    public function __destruct()
    {
        $this->connection->close();
    }
}
