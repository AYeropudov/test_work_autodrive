<?php

namespace Services\Storage;

use Models\ModelCollection;
use RuntimeException;

interface IStorageService
{

    /**
     * @param array $idis
     * @return ModelCollection
     * @throws RuntimeException
     */
    public function findExistOffers(array $idis): ModelCollection;

    /**
     * @param array $idis
     * @return ModelCollection
     * @throws RuntimeException
     */
    public function findExistGenerations(array $idis): ModelCollection;

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function addNewOffers(ModelCollection $modelCollection): int;

    /**
     * @param ModelCollection $modelCollection
     * @return int
     */
    public function addNewGenerations(ModelCollection $modelCollection): int;

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function updateOffers(ModelCollection $modelCollection): int;

    /**
     * @param ModelCollection $modelCollection
     * @return int
     * @throws RuntimeException
     */
    public function updateGenerations(ModelCollection $modelCollection): int;

    public function delNotExistOffers(array $existOffersIds);
}