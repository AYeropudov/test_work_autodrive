<?php

namespace Parsers;

use InvalidArgumentException;
use SplFileInfo;

class ParserFactory
{
    /**
     * @param SplFileInfo $fileInfo
     * @return IParser
     */
    static public function getParserForFile(SplFileInfo $fileInfo): IParser
    {
        switch ($fileInfo->getExtension()){
            case "xml": {
                return new ParserXml($fileInfo);
            }
            default:
            {
                throw new InvalidArgumentException("Unknown type parser `{$fileInfo->getExtension()}` given");
            }
        }

    }
}