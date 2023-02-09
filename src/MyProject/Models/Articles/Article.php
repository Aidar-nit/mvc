<?php
namespace MyProject\Models\Articles;

use MyProject\Models\Users\User;
use MyProject\Models\ActiveRecordEntity;

class Article extends ActiveRecordEntity
{
    
    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var int */
    protected $authorId;

    /** @var string */
    protected $createdAt;


    public function getAuthorId(): int
    {
        return (int) $this->authorId;
    }

     public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    
    protected static function getTableName():string
    {
        return 'articles';
    }

    

}