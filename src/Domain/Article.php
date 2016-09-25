<?php

namespace silex\Domain

class Article 
{
    /**
    * Article id
    *
    * @var integer
    */
    private $id;

    /**
    * Article title
    *
    * @var string
    */
    private $title;
    
    /**
    * Article content
    *
    * @var string
    */
    private $content;

    public function getId()
    {
        return $this->$id;
    }

    publilc function setId($id)
    {
        $this->$id = $id;
    }

    public function getTitle()
    {
        return $this->$title;
    }

    publilc function setTitle($title)
    {
        $this->$id = $title;
    }

    public function getContent()
    {
        return $this->$content;
    }

    public function setContent($content)
    {
        $this->$id = $  ;
    }


}

