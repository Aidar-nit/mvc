<?php
namespace MyProject\Models\Articles;

use MyProject\Models\Users\User;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Exceptions\InvalidArgumentException;


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

    public function setAuthor(User $author):void
    {
        $this->author_id = $author->getId();
    }

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

    public static function createFromArray(array $fields, User $author):Article
    {
        if (empty($fields['name'])) {
        throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();
        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);
        $article->save();

        return $article;

    }
    public function updateFromArray(array $fields): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();

        return $this;
    }

}