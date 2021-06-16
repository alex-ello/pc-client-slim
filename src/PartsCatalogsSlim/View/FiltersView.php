<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsSlim\FilterSet;
use PartsCatalogsSlim\FilterSetElement;
use PartsCatalogsSlim\FilterSetElementValue;
use PartsCatalogsSlim\FilterSetState;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\FilterView;

class FiltersView extends LayoutView
{
    /**
     * @var Catalog|null
     */
    public $catalog;

    /**
     * @var Model|null
     */
    public $model;

    /**
     * @var FilterSet|FilterSetElement[]
     */
    public $filterSet;

    public function catalogId(): string
    {
        return $this->catalog->id;
    }

    public function catalogName(): string
    {
        return $this->catalog->name;
    }

    public function modelName(): string
    {
        return $this->model->name;
    }

    public function carsCount(): int
    {
        return $this->filterSet->getCarsCount();
    }

    public function modelId(): string
    {
        return $this->model->id;
    }

    /**
     * @return FilterView[]
     */
    public function filtersView(): array
    {
        $list = [];
        foreach ($this->filterSet as $filter) {
            $list[] = new FilterView($filter);
        }
        return $list;
    }

    public function generateUrlForSelect(FilterSetElementValue $value)
    {
        $idxList = [];
        /** @var FilterSetElement $fsElement */
        foreach ($this->filterSet as $fsElement) {
            if ($fsElement->key == $value->getKey()) {
                continue;
            }
            $idxList[] = $fsElement->getSelectedValueIdx();
        }
        $idxList[] = $value->getIdx();

        $state = new FilterSetState($idxList);
        return Router::urlToFilters($this->catalogId(), $this->modelId(), $state->toString());
    }

    public function generateUrlForReset(FilterView $filterView): string
    {
        $idxList   = [];
        $filterKey = $filterView->key();
        $fs        = $this->filterSet;

        /** @var FilterSetElement $fsElement */
        foreach ($fs as $fsElement) {
            if ($fsElement->key == $filterKey) {
                continue;
            }
            $idxList[] = $fsElement->getSelectedValueIdx();
        }
        $state = new FilterSetState($idxList);

        return Router::urlToFilters($this->catalogId(), $this->modelId(), $state->toString());
    }

    public function filterState(): string
    {
        return $this->filterSet->getState()->toString();
    }

    /**
     * Get url to models page
     *
     * @return string
     */
    public function urlToModels(): string
    {
        return Router::urlToModels($this->catalogId());
    }

    public function urlToCars()
    {
        return Router::urlToCars($this->catalogId(), $this->modelId(), $this->filterState());
    }
}
