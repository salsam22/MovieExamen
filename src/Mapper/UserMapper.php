<?php

namespace App\Mapper;

use App\Registry;
use App\User;


class UserMapper
{
    protected \PDO $pdo;
    public function __construct()
    {
        $this->pdo = Registry::getPDO();
    }

    public function insert(User $obj)
    {
        $values = $obj->toArray();
        unset($values["id"]);
        $insertStmt = $this->pdo->prepare(
            "INSERT INTO user(username, password) 
            VALUES (:username, :password)");
        $insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $obj->setId((int)$id);
    }

    public function getPassword(User $user): string {
        $values = $user->toArray();
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username=:username");
        $stmt->bindValue("username", $values["username"]);
        $stmt->execute();
        $password = $stmt->fetch();
        return $password;
    }

}