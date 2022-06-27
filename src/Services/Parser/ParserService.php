<?php

namespace Services\Parser;

use Exception;
use Models\ModelCollection;
use Models\Offer;
use Parsers\IParser;
use Parsers\ParserFactory;
use Services\Import\IImportService;
use Services\Import\ImportService;
use Services\Storage\IStorageService;
use SplFileInfo;

class ParserService implements IParserService
{
    private SplFileInfo $file;
    private IStorageService $storageService;
    public function __construct(IStorageService $storageService)
    {
        $this->storageService = $storageService;
    }


    /**
     * Получим парсер для конкретного типа файла
     * @return IParser
     */
    private function getParser(): IParser{
        return ParserFactory::getParserForFile($this->getFile());
    }

    public function ping(): string
    {
        return "PING PONG";
    }

    /**
     * @param string $fileName
     * @return void
     * @throws Exception
     */
    public function processFile(string $fileName){
        if(!file_exists($fileName)){
            throw new Exception("Input file $fileName not found");
        }
        $this->file = new SplFileInfo($fileName);
        /**
         * @var $collection ModelCollection
         */
        $collection = $this->getParser()->parse();

        return $this->getImportService($collection)->runImport();
    }

    private function getFile(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return IStorageService
     */
    public function getStorageService(): IStorageService
    {
        return $this->storageService;
    }

    public function getImportService(ModelCollection $modelCollection): IImportService
    {
        return new ImportService($this->getStorageService(), $modelCollection);
    }
}