<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View\CarsView;

use PartsCatalogsSlim\FilterSetElement;
use PartsCatalogsSlim\FilterSetElementValue;
use PartsCatalogsSlim\View\LayoutView;

class FilterView extends LayoutView
{
    /**
     * @var FilterSetElement
     */
    private $fsElement;

    public function __construct(FilterSetElement $filter)
    {
        $this->fsElement = $filter;
    }

    public function key(): string
    {
        return $this->fsElement->key;
    }

    public function name(): string
    {
        return $this->fsElement->name;
    }

    public function isSelected(): bool
    {
        return $this->fsElement->isSelected();
    }

    public function value(): string
    {
        $value = $this->fsElement->getSelectedValue();
        if ($value !== null) {
            return $value->getValue();
        }

        return ' -';
    }

    /**
     * @return FilterSetElementValue[]
     */
    public function values(): array
    {
        return $this->fsElement->getValues();
    }

    public function hasUnAvailableValues(): bool
    {
        return $this->fsElement->hasUnAvailableValues();
    }
}
