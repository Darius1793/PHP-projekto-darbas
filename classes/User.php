<?php
require_once __DIR__ . "/Encryptor.php";

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password) {
    $checkSql = "SELECT id FROM users WHERE username = :username";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->execute([":username" => $username]);

    if ($checkStmt->fetch()) {
        return false;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $userKey = bin2hex(random_bytes(16));
    $encryptedKey = Encryptor::encrypt($userKey, $password);

    $sql = "INSERT INTO users (username, password_hash, encrypted_key)
            VALUES (:username, :password_hash, :encrypted_key)";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":username" => $username,
        ":password_hash" => $passwordHash,
        ":encrypted_key" => $encryptedKey
    ]);
}

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":username" => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password_hash"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_key"] = Encryptor::decrypt($user["encrypted_key"], $password);

            return true;
        }

        return false;
    }
}