<?php

namespace Adapters;

use PDO;
use PDOStatement;
use RuntimeException;

class MysqlAdapter implements IStorageAdapter
{
    private PDO $connection;
    public function __construct($databaseParam)
    {
        extract($databaseParam);
        $this->connection = new PDO("mysql:host=$host;port=3306;dbname=$dbname",$user, $password);
    }

    public function findMany(array $fields, array $condition, $tbl, array $join=[])
    {
        $joinStr = " ".join($join);
        array_walk($fields, function ($item) use ($tbl){
            return $tbl.".".$item;
        });
        $sqlQuery = "SELECT ".join(" ,", $fields)." FROM $tbl $joinStr WHERE ".join(" AND ", $condition);
        $sth = $this->getConnection()->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $res = $sth->execute();
        return $res? $sth->fetchAll(PDO::FETCH_ASSOC): $this->hadnleError($sth);
    }

    /**
     * @param array $fields
     * @param array $values
     * @param $tbl
     * @return int|void
     * @throws RuntimeException
     */
    public function insertMany(array $fields, array $values, $tbl)
    {
        $sqlQuery = "INSERT INTO $tbl (" .join(" ,", $fields).") VALUES ".join(", ", $values);
        $sth = $this->getConnection()->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $res = $sth->execute();
        if ($res) {
            return $sth->rowCount();
        }
        $this->hadnleError($sth);
    }

    /**
     * @param array $values
     * @param array $condition
     * @param string $tbl
     * @return int|void
     * @throws RuntimeException
     */
    public function updateOne(array $values, array $condition, string $tbl)
    {
        $sqlQuery = "UPDATE $tbl SET " .join(" ,", $values)." WHERE ".join(" AND ", $condition);
        $sth = $this->getConnection()->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $res = $sth->execute();
        if ($res) {
            return $sth->rowCount();
        }
        $this->hadnleError($sth);
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param PDOStatement $sth
     * @throws RuntimeException
     */
    private function hadnleError(PDOStatement $sth){
        $err = $sth->errorInfo();
        throw new RuntimeException($err[2]);
    }
}