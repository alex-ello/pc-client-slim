<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class ModelCollection extends AbstractCollection
{
    /**
     * ModelCollection constructor.
     *
     * @param array[] $array
     * @param int      $total
     */
    public function __construct(iterable $array)
    {
        foreach ($array as $item) {
            $this->add(Model::fromArray($item));
        }
    }
}
