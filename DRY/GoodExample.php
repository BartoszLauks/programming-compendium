<?php

declare(strict_types=1);

class Database
{
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli('localhost', 'user', 'password', 'database');
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function close()
    {
        $this->connection->close();
    }
}

class User
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getUserData($id)
    {
        $result = $this->db->query("SELECT * FROM users WHERE id = " . $id);
        return $result->fetch_assoc();
    }

    public function getUserPosts($id)
    {
        $result = $this->db->query("SELECT * FROM posts WHERE user_id = " . $id);
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }
}

// Użycie
$db = new Database();
$user = new User($db);
$userData = $user->getUserData(1);
$userPosts = $user->getUserPosts(1);
$db->close();
