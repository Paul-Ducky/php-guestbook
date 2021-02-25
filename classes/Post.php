<?php

use JetBrains\PhpStorm\ArrayShape;

class Post
{
    private string $title;
    private string $date;
    private string $content;
    private string $author;


    public function __construct(string $title, string $content, string $author)
    {
        $this->title = $title;
        $this->date = date('l jS \of F Y h:i A');
        $this->content = $content;
        $this->author = $author;

    }

    #[ArrayShape(['title' => "string", 'date' => "string", 'content' => "string", 'author' => "string"])]
    public function toArray():array{
        return
            [
            'title'=>$this->title,
            'date'=>$this->date,
            'content'=>$this->content,
            'author'=>$this->author
            ];
    }

}