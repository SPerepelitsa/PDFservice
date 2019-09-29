<?php

namespace App\DTO;


final class PdfFileDTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $keyWords;

    /**
     * @var string
     */
    private $metaInfo;

    public function __construct($title, $description, $keyWords, $metaInfo)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keyWords = $keyWords;
        $this->metaInfo = $metaInfo;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getKeyWords()
    {
        return $this->keyWords;
    }

    /**
     * @return string
     */
    public function getMetaInfo()
    {
        return $this->metaInfo;
    }
}
