<?php

namespace Models;

use ValueError;

class Generation implements IModel
{
    private int $id;
    private string $title;

    /**
     * @param int $generation_id
     * @param string $title
     */
    public function __construct(int $generation_id, string $title)
    {
        $this->id = $generation_id;
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAttr(string $attrName)
    {
        if(property_exists($this, $attrName)){
            return $this->$attrName;
        }
        throw new ValueError("An error happened, due retrive Generation property `$attrName`");
    }

    public static function tblName(): string
    {
        return "generations";
    }

    public function getInsertQuery(): string
    {
        return "($this->id, '$this->title')";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return strval($this->id);
    }

    public function __set($name, $value)
    {
        // empty
    }
}