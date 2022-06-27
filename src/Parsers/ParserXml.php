<?php

namespace Parsers;

use Exception;
use Models\ModelCollection;
use Models\Offer;
use SimpleXMLIterator;
use SplFileInfo;

class ParserXml implements IParser
{
    private SplFileInfo $file;
    private ModelCollection $offers;
    public function __construct(SplFileInfo $splFileInfo)
    {
        $this->file = $splFileInfo;
        $this->offers = new ModelCollection();
    }

    /**
     * @throws Exception
     */
    public function parse(): ModelCollection
    {
        $itemIterator = new SimpleXmlIterator($this->file->getPathname(), null, true);
        $itemIterator->rewind();
        /*Получаем корень `offers`, и терируриуемся по нему*/
        $root = $itemIterator->current();
        $root->rewind();
        while (!is_null($root->current())){
            /*по каждому `offer` в xml сериализуем в обьект*/
            $this->offers->append((new Offer(self::class, $root->current())));
            $root->next();
        }
        return $this->offers;
    }
}