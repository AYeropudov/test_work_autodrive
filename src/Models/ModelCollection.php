<?php

namespace Models;

use ArrayObject;

class ModelCollection extends ArrayObject
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function getColumn(callable $callback): array {
        return array_map(
            $callback,
            $this->getArrayCopy()
        );
    }

    /**
     * @param callable $callback
     * @return ModelCollection
     */
    public function filterBy(callable $callback): ModelCollection
    {
        return new ModelCollection(array_filter($this->getArrayCopy(), $callback));
    }

    public function difference(ModelCollection $modelCollection):ModelCollection{
        return new ModelCollection(array_diff($this->getArrayCopy(), $modelCollection->getArrayCopy()));
    }

}