<?php

declare(strict_types=1);

class UserManager
{
    public function createUser(array $data)
    {
        if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
            // Tworzenie użytkownika
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

            // Zapis do bazy danych
            $db = new DatabaseConnection();
            $db->connect('localhost', 'root', 'password', 'database');
            $query = "INSERT INTO users (name, email, password) VALUES ('".$user->getName()."', '".$user->getEmail()."', '".$user->getPassword()."')";
            $db->query($query);

            return $user;
        } else {
            throw new Exception('Invalid data');
        }
    }
}
