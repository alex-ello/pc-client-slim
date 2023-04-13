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

    public function add($item): void
    {
        $this->collection[] = $item;
    }

    public function current(): mixed
    {
        return current($this->collection);
    }

    public function next(): void
    {
        next($this->collection);
    }

    public function key(): mixed
    {
        return key($this->collection);
    }

    public function valid(): bool
    {
        return key($this->collection) !== null;
    }

    public function rewind(): void
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
