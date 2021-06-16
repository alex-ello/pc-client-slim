<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarsParametersCollection extends AbstractCollection
{
    private $carsCount;
    private $map;

    public function __construct($array, int $carsCount)
    {
        $this->carsCount = $carsCount;

        foreach ($array as $item) {
            $cpi = CarParameterInfo::fromArray($item);
            $this->add($cpi);
            foreach ($cpi->values as $value) {
                $this->map[$value->idx] = true;
            }
        }
    }

    /**
     * @return int
     */
    public function getCarsCount(): int
    {
        return $this->carsCount;
    }

    public function idxIsAvailable(string $idx): bool
    {
        return isset($this->map[$idx]);
    }

    function getList(): iterable
    {
        return $this;
    }
}
