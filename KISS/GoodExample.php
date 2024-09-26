<?php

declare(strict_types=1);

//TODO : Make better example
class User
{
    private $name;
    private $email;
    private $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

class Database
{
    private $connection;

    public function __construct($host, $user, $pass, $dbname)
    {
        $this->connection = new mysqli($host, $user, $pass, $dbname);
    }

    public function saveUser(User $user): bool
    {
        $stmt = $this->connection->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

        // Only virables can be passed by reference
        $email = $user->getEmail();
        $name = $user->getName();
        $password = $user->getPassword();

        $stmt->bind_param("sss", $name, $email, $password);

        return $stmt->execute();
    }
}

class UserManager
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createUser(string $name, string $email, string $password): User
    {
        $user = new User($name, $email, $password);
        $this->db->saveUser($user);

        return $user;
    }
}


