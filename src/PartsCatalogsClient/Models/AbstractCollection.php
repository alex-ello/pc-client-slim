<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

use Countable;
use Iterator;

abstract class AbstractCollection implements Iterator, Countable
{
    /**
     * @var array
     */
    private $collection = [];

    public function add($item)
    {
        $this->collection[] = $item;
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        next($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return key($this->collection) !== null;
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function sortAndGroup(callable $sortFn, callable $groupFn): array
    {
        $list = $this->collection;
        uasort($list, $sortFn);

        $groups = [];
        foreach ($list as $item) {
            $group            = $groupFn($item);
            $groups[$group][] = $item;
        }

        return $groups;
    }
}
