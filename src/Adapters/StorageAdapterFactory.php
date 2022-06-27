<?php

namespace Adapters;

class StorageAdapterFactory
{
    public static function getAdapter(string $AdapterClassName, array $databaseConfig){
        return new $AdapterClassName($databaseConfig);
    }
}