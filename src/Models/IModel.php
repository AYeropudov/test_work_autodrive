<?php

namespace Models;

interface IModel
{
    public static function tblName();
    public function getAttr(string $attrName);
    public function getInsertQuery();
    public function __toString();
}