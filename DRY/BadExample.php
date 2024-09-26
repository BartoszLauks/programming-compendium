<?php

declare(strict_types=1);

class User
{
    public function getUserData($id)
    {
        // Połączenie z bazą danych
        $db = new mysqli('localhost', 'user', 'password', 'database');

        // Zapytanie do bazy danych
        $result = $db->query("SELECT * FROM users WHERE id = " . $id);
        $user = $result->fetch_assoc();

        // Zamknięcie połączenia
        $db->close();

        return $user;
    }

    public function getUserPosts($id)
    {
        // Połączenie z bazą danych
        $db = new mysqli('localhost', 'user', 'password', 'database');

        // Zapytanie do bazy danych
        $result = $db->query("SELECT * FROM posts WHERE user_id = " . $id);
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        // Zamknięcie połączenia
        $db->close();

        return $posts;
    }
}
