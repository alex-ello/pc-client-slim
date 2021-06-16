<?php declare(strict_types=1);

namespace PartsCatalogsSlim;

use PartsCatalogsClient\Models\AbstractCollection;
use PartsCatalogsClient\Models\CarParameterInfo;
use PartsCatalogsClient\Models\CarsParametersCollection;

/**
 * Class FilterSet
 *
 * @returns FilterSetState[]
 * @package PartsCatalogsSlim
 */
class FilterSet extends AbstractCollection
{
    /**
     * @var FilterSetState
     */
    private $state;

    /**
     * @var int
     */
    private $carsCount;

    /**
     * @var CarParameterInfo[]|CarsParametersCollection
     */
    private $cpAll;

    /**
     * @var CarParameterInfo[]|CarsParametersCollection
     */
    private $cpAvailable;

    /**
     * FilterSet constructor.
     *
     * @param CarsParametersCollection $cpAll
     * @param CarsParametersCollection $cpAvailable
     * @param FilterSetState|null      $state
     */
    public function __construct(CarsParametersCollection $cpAll, CarsParametersCollection $cpAvailable, FilterSetState $state = null)
    {
        if ($state == null) {
            $state = new FilterSetState([]);
        }
        $this->state       = $state;
        $this->cpAll       = $cpAll;
        $this->cpAvailable = $cpAvailable;
        $this->carsCount   = $cpAvailable->getCarsCount();

        foreach ($cpAll as $cp) {
            $this->add($this->carParameterToFilterSetElement($cp));
        }
    }

    private function carParameterToFilterSetElement(CarParameterInfo $carParamInfo): FilterSetElement
    {
        $element = new FilterSetElement($carParamInfo->key, $carParamInfo->name, $this);
        foreach ($carParamInfo->values as $cpValue) {
            $value = new FilterSetElementValue($cpValue, $element);
            $element->addValue($value);
        }

        return $element;
    }

    public function idxIsAvailable(string $idx): bool
    {
        return $this->cpAvailable->idxIsAvailable($idx);
    }

    public function idxIsSelected(string $idx): bool
    {
        return $this->state->hasIdx($idx);
    }

    public function getState(): FilterSetState
    {
        $idxList = [];
        foreach ($this as $element) {
            $idxList[] = $element->getSelectedValueIdx();
        }
        return new FilterSetState($idxList);
    }

    public function hasFilter($key): bool
    {
        foreach ($this as $element) {
            if ($element->key == $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return count of selected filters
     *
     * @return int
     */
    public function selectedFilterCount(): int
    {
        $c = 0;
        foreach ($this as $element) {
            if ($element->isSelected()) {
                $c++;
            }
        }
        return $c;
    }

    public function getFilterByKey(string $key): ?FilterSetElement
    {
        foreach ($this as $filter) {
            if ($filter->key == $key) {
                return $filter;
            }
        }
        return null;
    }

    public function getCarsCount(): int
    {
        return $this->carsCount;
    }

}
