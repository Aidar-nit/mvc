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
    protected $author_id;

    /** @var string */
    protected $created_at;

    public function setName(string $name):void
    {
        $this->name = $name;
    }
    public function setText(string $text):void
    {
        $this->text = $text;
    }


    public function getAuthorId(): int
    {
        return (int) $this->author_id;
    }

     public function getAuthor(): User
    {
        return User::getById($this->author_id);
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