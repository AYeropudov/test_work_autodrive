<?php

namespace Services\Storage;

use Adapters\IStorageAdapter;
use Adapters\StorageAdapterFactory;
use Models\Generation;
use Models\IModel;
use Models\ModelCollection;
use Models\Offer;
use RuntimeException;

class StorageService implements IStorageService
{

    private IStorageAdapter $storageAdapter;

    public function __construct(string $adapter, array $confDb)
    {
        $this->storageAdapter = StorageAdapterFactory::getAdapter($adapter, $confDb);
    }

    /**
     * @param array $idis
     * @return ModelCollection
     */
    public function findExistOffers(array $idis): ModelCollection{
        $res = $this->getStorageAdapter()->findMany(["offers.id", "offers.model", "offers.mark","offers.year", "offers.run", "offers.color", "offers.body_type", "offers.engine_type", "offers.transmission", "offers.gear_type", "offers.generation_id", "g.title as generation"], ["offers.id in (".join(",",$idis).")"], Offer::tblName(), ["left join generations g on offers.generation_id=g.id"]);
        $res = array_map(function ($item) {
            return new Offer("array", $item);
        }, $res);
        return new ModelCollection($res);
    }

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function addNewOffers(ModelCollection $modelCollection): int{

        if($modelCollection->count() === 0) {
            return 0;
        }
        $valuesList = [];
        $iterator = $modelCollection->getIterator();
        $iterator->rewind();
        while (!is_null($iterator->current())){
            /** @var IModel $itm */
            $itm = $iterator->current();
            $valuesList[]=$itm->getInsertQuery();
            $iterator->next();
        }
        return $this->getStorageAdapter()->insertMany(['id', 'model','mark', 'year', 'run', 'color', 'body_type', 'engine_type', 'gear_type', 'transmission', 'generation_id'], $valuesList, Offer::tblName());
    }

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function updateOffers(ModelCollection $modelCollection): int{
        if($modelCollection->count() === 0) {
            return 0;
        }
        $iterator = $modelCollection->getIterator();
        $iterator->rewind();
        $counter = 0;
        while (!is_null($iterator->current())){
            /** @var IModel $itm */
            $itm = $iterator->current();
            $this->getStorageAdapter()->updateOne([
                "model='{$itm->getAttr("model")}'",
                "mark='{$itm->getAttr("mark")}'",
                "year={$itm->getAttr("year")}",
                "run={$itm->getAttr("run")}",
                "color='{$itm->getAttr("color")}'",
                "body_type='{$itm->getAttr("bodyType")}'",
                "engine_type='{$itm->getAttr("engineType")}'",
                "transmission='{$itm->getAttr("transmission")}'",
                "gear_type='{$itm->getAttr("gearType")}'",
                ($itm->getAttr("generationId"))? "generation_id=".$itm->getAttr('generationId'):"generation_id=null",

            ], ['id='.$itm->getAttr("id")], Offer::tblName());
            $counter++;
            $iterator->next();
        }
        return $counter;
    }

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function addNewGenerations(ModelCollection $modelCollection): int{
        if($modelCollection->count() === 0) {
            return 0;
        }
        $valuesList = [];
        $iterator = $modelCollection->getIterator();
        $iterator->rewind();
        while (!is_null($iterator->current())){
            /** @var IModel $itm */
            $itm = $iterator->current();
            $valuesList[]=$itm->getInsertQuery();
            $iterator->next();
        }
        return $this->getStorageAdapter()->insertMany(['id', 'title'], $valuesList, Generation::tblName());
    }

    /**
     * @return IStorageAdapter
     */
    public function getStorageAdapter(): IStorageAdapter
    {
        return $this->storageAdapter;
    }

    /**
     * @param array $idis
     * @return ModelCollection
     * @throws RuntimeException
     */
    public function findExistGenerations(array $idis): ModelCollection
    {
        $res = $this->getStorageAdapter()->findMany(["id, title"], ["id in (".join(",",$idis).")"], Generation::tblName());
        $res = array_map(function ($item) {
            return new Generation(intval($item["id"]), $item['title']);
        }, $res);
        return new ModelCollection($res);
    }

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function updateGenerations(ModelCollection $modelCollection): int
    {
        if($modelCollection->count() === 0) {
            return 0;
        }
        $iterator = $modelCollection->getIterator();
        $iterator->rewind();
        $counter = 0;
        while (!is_null($iterator->current())){
            /** @var IModel $itm */
            $itm = $iterator->current();
            $this->getStorageAdapter()->updateOne(["title='{$itm->getAttr("title")}'"], ['id='.$itm->getAttr("id")], Generation::tblName());
            $counter++;
            $iterator->next();
        }
        return $counter;
    }
}