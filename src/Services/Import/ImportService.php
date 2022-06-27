<?php

namespace Services\Import;

use Models\Generation;
use Models\IModel;
use Models\ModelCollection;
use Services\Storage\IStorageService;

class ImportService implements IImportService
{
    private IStorageService $storageService;

    private ModelCollection $modelCollection;

    private int $generationsInserted;


    private int $generationsUpdated;
    private int $offersInserted;
    private int $offersUpdated;
    private int $offersDeleted;

    /**
     * @param IStorageService $storageService
     * @param ModelCollection $modelCollection
     */
    public function __construct(IStorageService $storageService, ModelCollection $modelCollection)
    {
        $this->storageService = $storageService;
        $this->modelCollection = $modelCollection;
    }

    /**
     * @return IStorageService
     */
    public function getStorageService(): IStorageService
    {
        return $this->storageService;
    }

    /**
     * @return ModelCollection
     */
    public function getModelCollection(): ModelCollection
    {
        return $this->modelCollection;
    }

    public function runImport(): array
    {
        //Обработка  поколений АВТО
        $this->processGenerations();

        // Обработка оферов
        $this->processOffers();

        return [
            ["title" => "Удвлено Обьявлений", "count" => $this->offersDeleted],
            ["title" => "Добавлено новых Обьявлений", "count" => $this->offersInserted],
            ["title" => "Обновлено Обьявлений", "count" => $this->offersUpdated],
            ["title" => "Добавлено новых Поколений", "count" => $this->generationsInserted],
            ["title" => "Обновлено Поколений", "count" => $this->generationsUpdated]
        ];
    }
    private function processGenerations(){
        $generationsInDump = $this->getGenerationsInDump();
        $idis = $generationsInDump->getColumn(
            function (IModel $item) {
                return $item->getAttr("id");
            }
        );
        $generationsInDb = $this->getStorageService()->findExistGenerations($idis);
        $generationsToCreate = $generationsInDump->difference($generationsInDb);
        $generationsCandidateToUpdate = $generationsInDump->difference($generationsToCreate);
        $generationsInDbArray = $generationsInDb->getArrayCopy();
        $generationsToUpdate = $generationsCandidateToUpdate->filterBy(
            function (IModel $item) use ($generationsInDbArray){
                $x = in_array($item, $generationsInDbArray);
                return !$x;
            });
        $resCreate = $this->getStorageService()->addNewGenerations($generationsToCreate);
        $this->setGenerationsInserted($resCreate);
        $resUpdate = $this->getStorageService()->updateGenerations($generationsToUpdate);
        $this->setGenerationsUpdated($resUpdate);
    }

    private function processOffers(){
        #Clear DB before import
        $cleared = $this->clearDb();
        $this->setOffersDeleted($cleared);
        $offersInDb = $this->getOffersInDb();

        $offersToCreate = $this->getModelCollection()->difference($offersInDb);
        $offersCandidateToUpdate = $this->getModelCollection()->difference($offersToCreate);
        $offersInDbAsArray = $offersInDb->getArrayCopy();
        $offersToUpdate = $offersCandidateToUpdate->filterBy(
            function (IModel $item) use ($offersInDbAsArray){
                return !in_array($item, $offersInDbAsArray);
            });
        $this->setOffersInserted($this->getStorageService()->addNewOffers($offersToCreate));
        $this->setOffersUpdated($this->getStorageService()->updateOffers($offersToUpdate));
    }
    /**
     * @return ModelCollection
     */
    private function getOffersInDb(): ModelCollection
    {
        $existOffersIds = $this->getModelCollection()->getColumn(function ($item) {return $item->getAttr("id");});
        return $this->getStorageService()->findExistOffers($existOffersIds);
    }

    /**
     * @return ModelCollection
     */
    private function getGenerationsInDump(): ModelCollection
    {
        $newGenItems = $this->getModelCollection()->filterBy(
            function (IModel $item) {
                return $item->getAttr("generationId") !== 0;
            }
        );

        return new ModelCollection(array_unique(array_map(function (IModel $item){
            return new Generation($item->getAttr("generationId"), $item->getAttr("generation"));
        }, $newGenItems->getArrayCopy())));
    }

    /**
     * @param int $generationsInserted
     */
    public function setGenerationsInserted(int $generationsInserted): void
    {
        $this->generationsInserted = $generationsInserted;
    }

    /**
     * @param int $generationsUpdated
     */
    public function setGenerationsUpdated(int $generationsUpdated): void
    {
        $this->generationsUpdated = $generationsUpdated;
    }

    /**
     * @param int $offersInserted
     */
    public function setOffersInserted(int $offersInserted): void
    {
        $this->offersInserted = $offersInserted;
    }

    /**
     * @param int $offersUpdated
     */
    public function setOffersUpdated(int $offersUpdated): void
    {
        $this->offersUpdated = $offersUpdated;
    }

    private function clearDb()
    {
        $existOffersIds = $this->getModelCollection()->getColumn(function ($item) {return $item->getAttr("id");});
        return $this->getStorageService()->delNotExistOffers($existOffersIds);
    }

    /**
     * @param int $offersDeleted
     */
    public function setOffersDeleted(int $offersDeleted): void
    {
        $this->offersDeleted = $offersDeleted;
    }

}