<?php

namespace MyProject\Models\Users;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Exceptions\InvalidArgumentException;

class User extends ActiveRecordEntity
{
     /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $is_confirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $password_hash;

    /** @var string */
    protected $auth_token;

    /** @var string */
    protected $created_at;

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }
    public function getPasswordHash():string
    {
        return $this->password_hash;
    }
    public function getAuthToken():string
    {
        return $this->auth_token;
    }
    protected static function getTableName(): string
    {
        return 'users';
    }
    public function isConfirmed()
    {
        return $this->is_confirmed;
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public static function signUp(array $userData):User
    {
        if (empty($userData['name'])) {
            throw new InvalidArgumentException('Field name is empty please Enter name');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/',$userData['name'])) {
            throw new InvalidArgumentException('Nickname can only consist of Latin characters and numbers');
        }
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Field email is empty please Enter email');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please enter a valid email address.');
        }
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Field password is empty please Enter password');
        }
        if (mb_strlen($userData['password']) < 8) {
            throw new InvalidArgumentException('Please enter password more than 8 characters');
        }

        if (static::findOneByColumn('nickname',$userData['name']) !== null) {
            throw new InvalidArgumentException('A user with the same name already exists');
        }
        if (static::findOneByColumn('email',$userData['email'] !== null)) {
            throw new InvalidArgumentException('A user with the same email already exists');
        }

        $user = new User();
        $user->nickname = $userData['name'];
        $user->email = $userData['email'];
        $user->password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->is_confirmed = false;
        $user->role = 'user';
        $user->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();
        return $user;
    }


    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }


    public static function login(array $loginData):User
    {
       
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        $user = User::findOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким email');
        }
        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }
        var_dump($user->isConfirmed());
        if (!$user->is_confirmed) {
            throw new InvalidArgumentException('Пользователь не подтверждён');
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;

    }

    private function refreshAuthToken()
    {
        $this->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }


}