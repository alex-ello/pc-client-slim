<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CatalogCollection extends AbstractCollection
{
    /**
     * CatalogCollection constructor.
     *
     * @param array[] $array
     */
    public function __construct(iterable $array)
    {
        foreach ($array as $item) {
            $this->add(Catalog::fromArray($item));
        }
    }
}
