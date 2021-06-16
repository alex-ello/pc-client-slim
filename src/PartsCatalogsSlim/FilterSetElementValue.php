<?php declare(strict_types=1);

namespace PartsCatalogsSlim;

use PartsCatalogsClient\Models\CarParameterInfo;
use PartsCatalogsClient\Models\CarParameterValue;

class FilterSetElementValue
{
    /**
     * @var CarParameterInfo
     */
    private $carParameterValue;

    /**
     * @var FilterSet
     */
    private $fsElementRef;

    public function __construct(CarParameterValue $cpValue, FilterSetElement $fsElement)
    {
        $this->carParameterValue = $cpValue;
        $this->fsElementRef      = $fsElement;
    }

    /**
     * Return true if value is selected in current filters set
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->fsElementRef->filterSetRef->idxIsSelected($this->getIdx());
    }

    /**
     * Return true if value is available in current filters set
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->fsElementRef->filterSetRef->idxIsAvailable($this->getIdx());
    }

    /**
     * Return value key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->fsElementRef->key;
    }

    /**
     * @return string
     */
    public function getIdx(): string
    {
        return $this->carParameterValue->idx;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->carParameterValue->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
