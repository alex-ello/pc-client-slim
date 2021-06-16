<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

use PartsCatalogsClient\GroupPath;

class GroupCollection extends AbstractCollection
{
    /**
     * CatalogCollection constructor.
     *
     * @param array[]   $array
     * @param GroupPath $groupPath
     */
    public function __construct(iterable $array, GroupPath $groupPath)
    {
        foreach ($array as $item) {
            $newGroupPath = clone $groupPath;
            $newGroupPath->addId($item['id']);
            $this->add(Group::fromArray($item)->setGroupPath($newGroupPath));
        }
    }
}
