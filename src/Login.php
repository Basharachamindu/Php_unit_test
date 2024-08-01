<?php

namespace App;

class Login {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function authenticate($id, $password) {
        $sql = "SELECT * FROM login WHERE id=? AND password=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $id, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_array(MYSQLI_ASSOC);
        return $user !== null;
    }
}
