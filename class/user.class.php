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
        // Generate unkID and usrID once
        $unkID = $this->generateUnkID();
        $usrID = $this->generateUsrID();

        // Convert data array to comma-separated strings
        $columns = implode(", ", array_merge(array_keys($data), ['unk_id', 'usr_id']));
        $values = "'" . implode("', '", array_merge(array_values($data), [$unkID, $usrID])) . "'";

        // Check if phone and email are unique
        $checkUserData = json_decode($this->checkUser($data['phone'], $data['email']));

        // If both phone and email are unique, insert the new user
        if (!$checkUserData->isPhone && !$checkUserData->isEmail) {
            reGen:
            $checkUsridUnkid = json_decode($this->checkUnkIDUsrID($unkID, $usrID));
            if (!$checkUsridUnkid->isUnkid && !$checkUsridUnkid->isUsrid) {
                $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
                if ($this->connection->query($sql)) {
                    return array('message' => 'User created successfully 😊');
                } else {
                    return array('message' => 'Failed to create User 😞');
                }
            } else {
                // Regenerate IDs and try again
                $unkID = $this->generateUnkID();
                $usrID = $this->generateUsrID();
                goto reGen;
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
        $sql = "SELECT * FROM $table WHERE usr_id = $id";
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
        $isUnkid = $this->connection->query("SELECT * FROM users WHERE unk_id = '$unkid'")->num_rows > 0;
        $isUsrid = $this->connection->query("SELECT * FROM users WHERE usr_id = '$usrid'")->num_rows > 0;

        // Return JSON response indicating phone and email status
        return json_encode(array('isUnkid' => $isUnkid, 'isUsrid' => $isUsrid));
    }

    private function generateUnkID()
    {
        $randomInt = mt_rand(100, 999); // Generate a random 3-digit integer
        $randomChar = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3); // Generate a random 3-character string
        return "edu-$randomInt-com-$randomChar";
    }

    private function generateUsrID()
    {
        return mt_rand(1000000000, 9999999999); // Generate a random 10-digit integer
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
