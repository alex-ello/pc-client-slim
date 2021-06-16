<?php declare(strict_types=1);

namespace PartsCatalogsSlim;

class FilterSetElement
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $name;

    /**
     * @var FilterSetElementValue[]
     */
    public $values;

    /**
     * @var FilterSet
     */
    public $filterSetRef;

    /**
     * @var string
     */
    private $selectedIdx;

    public function __construct(string $key, string $name, FilterSet $filterSet)
    {
        $this->key          = $key;
        $this->name         = $name;
        $this->filterSetRef = $filterSet;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return FilterSetElementValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return FilterSetElementValue|null
     */
    public function getSelectedValue(): ?FilterSetElementValue
    {
        if (isset($this->values[$this->selectedIdx])) {
            return $this->values[$this->selectedIdx];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getSelectedValueIdx(): ?string
    {
        if ($this->selectedIdx) {
            return $this->selectedIdx;
        }
        return null;
    }

    /**
     * @param FilterSetElementValue $value
     *
     * @return $this
     */
    public function addValue(FilterSetElementValue $value): self
    {
        $idx                = $value->getIdx();
        $this->values[$idx] = $value;

        if ($value->isSelected()) {
            $this->selectedIdx = $idx;
        }

        return $this;
    }

    public function isSelected(): bool
    {
        return $this->selectedIdx !== null;
    }

    public function hasUnAvailableValues()
    {
        foreach ($this->values as $value) {
            if (!$this->filterSetRef->idxIsAvailable($value->getIdx())) {
                return true;
            }
        }
        return false;
    }

    public function getValueByKey(string $key): ?FilterSetElementValue
    {
        foreach ($this->values as $value) {
            if ($value->getKey() == $key)
                return $value;
        }

        return null;
    }
}
