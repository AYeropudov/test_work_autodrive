<?php

namespace Adapters;

interface IStorageAdapter
{
    public function findMany(array $fields, array $condition, string $tbl, array $join);

    public function insertMany(array $fields, array $values, string $tbl);

    public function updateOne(array $values, array $condition, string $tbl);

    public function delete(array $condition, string $tbl);

}