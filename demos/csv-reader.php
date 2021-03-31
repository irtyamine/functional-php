<?php

use FunctionalPhp\SessionInterface;

class User
{
    public string $username;
    public string $name;
    public string $email;
}

/** @var SessionInterface $session */
$session
    ->read('csv', ['file' => 'users.csv'])
    ->unique('username')
    ->combineKeys(['username', 'name', 'email'])
    ->unserialize(User::class)
    ->write('doctrine')
;
