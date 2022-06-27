<?php

namespace Services\Parser;

/**
 * Интерфейс для сервиса парсинга
 */
interface IParserService
{
    public function ping();

    /**
     * Обработка файла
     * @param string $fileName
     * @return mixed
     */
    public function processFile(string $fileName);
}