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

    protected static function getTableName(): string
    {
        return 'users';
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
}