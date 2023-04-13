<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

use Iterator;

class CarParameters implements Iterator
{
    private $list = [];

    /**
     * @var CarParameter[string]
     */
    private $map = [];

    public static function fromArray($array): CarParameters
    {
        $obj = new CarParameters();

        foreach ($array as $item) {
            $cp = CarParameter::fromArray($item);
            $obj->list[] = $cp;
            $obj->map[$cp->key] = $cp;
        }

        return $obj;
    }

    public function get($key) {
        if (isset($this->map[$key])) {
            return $this->map[$key];
        }

        return null;
    }

    public function getValue(string $key): ?string
    {
        if (isset($this->map[$key])) {
            return $this->map[$key]->value;
        }
        return null;
    }

    /**
     * Return the current element
     * @link  https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current(): mixed
    {
        return current($this->list);
    }

    /**
     * Move forward to next element
     * @link  https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next(): void
    {
        next($this->list);
    }

    /**
     * Return the key of the current element
     * @link  https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key(): mixed
    {
        return key($this->list);
    }

    /**
     * Checks if current position is valid
     * @link  https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return key($this->list) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link  https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind(): void
    {
        reset($this->list);
    }

    public function getRegionValue()
    {
        if ($this->has(CarParameter::KEY_SALES_REGION)) {
            return $this->getValue(CarParameter::KEY_SALES_REGION);
        }

        return null;
    }

    public function has($key)
    {
        return isset($this->map[$key]);
    }
}
