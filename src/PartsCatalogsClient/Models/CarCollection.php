<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarCollection extends AbstractCollection
{
    /**
     * @var int Total cars in DB
     */
    private $total;

    /**
     * CarCollection constructor.
     *
     * @param array[] $array
     * @param int      $total
     */
    public function __construct(iterable $array, int $total)
    {
        $this->total = $total;
        foreach ($array as $item) {
            $this->add(Car::fromArray($item));
        }
    }

    public function total()
    {
        return $this->total;
    }
}
