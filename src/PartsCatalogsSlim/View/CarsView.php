<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Car;
use PartsCatalogsClient\Models\CarCollection;
use PartsCatalogsClient\Models\CarParameter;
use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsSlim\FilterSet;
use PartsCatalogsSlim\FilterSetElement;
use PartsCatalogsSlim\FilterSetElementValue;
use PartsCatalogsSlim\FilterSetState;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\CarView;
use PartsCatalogsSlim\View\CarsView\FilterView;

class CarsView extends LayoutView
{
    /**
     * @var Catalog
     */
    public $catalog;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var FilterSet|FilterSetElement[]
     */
    public $filterSet;

    /**
     * @var CarCollection
     */
    public $cars;

    const PRIMARY_PARAMS = [
        CarParameter::KEY_YEAR,
        CarParameter::KEY_SALES_REGION,
        CarParameter::KEY_STEERING,
        CarParameter::KEY_TRANSMISSION,
        CarParameter::KEY_TRANSMISSION_TYPE,
        CarParameter::KEY_BODY,
        CarParameter::KEY_BODY_TYPE,
        CarParameter::KEY_ENGINE,
        CarParameter::KEY_ENGINE_CODE,
    ];

    public function __construct(Catalog $catalog, Model $model, CarCollection $cars, FilterSet $filterSet)
    {
        $this->catalog   = $catalog;
        $this->cars      = $cars;
        $this->model     = $model;
        $this->filterSet = $filterSet;
    }

    public function hasFilters(): bool
    {
        return $this->filterSet->count() > 0;
    }

    public function catalogId(): string
    {
        return $this->catalog->id;
    }

    public function catalogName(): string
    {
        return $this->catalog->name;
    }

    public function modelId(): string
    {
        return $this->model->id;
    }

    public function modelName(): string
    {
        return $this->model->name;
    }

    /**
     * @return CarView[]
     */
    public function cars(): iterable
    {
        // Convert CarCollection to CarView list
        return array_map(function (Car $car) {
            return CarView::fromCar($car);
        }, iterator_to_array($this->cars));
    }

    public function carsCount(): int
    {
        return $this->cars->count();
    }

    public function carsTotal(): int
    {
        return $this->cars->total();
    }

    public function hasYear(): bool
    {
        return $this->filterSet->hasFilter(CarParameter::KEY_YEAR);
    }

    public function hasRegion(): bool
    {
        return $this->filterSet->hasFilter(CarParameter::KEY_SALES_REGION);
    }

    public function hasSteering(): bool
    {
        return $this->filterSet->hasFilter(CarParameter::KEY_STEERING);
    }

    public function hasEngine(): bool
    {
        if ($this->filterSet->hasFilter(CarParameter::KEY_ENGINE)) {
            return true;
        }
        if ($this->filterSet->hasFilter(CarParameter::KEY_ENGINE_CODE)) {
            return true;
        }
        return false;
    }

    public function hasEngineCode(): bool
    {
        return $this->filterSet->hasFilter(CarParameter::KEY_ENGINE_CODE);
    }

    public function hasTransmissionSimple(): bool
    {
        if ($this->filterSet->hasFilter(CarParameter::KEY_TRANSMISSION)) {
            return true;
        }
        if ($this->filterSet->hasFilter(CarParameter::KEY_TRANSMISSION_TYPE)) {
            return true;
        }
        return false;
    }

    /**
     * @return FilterView[]
     */
    public function filters(): array
    {
        $list = [];
        foreach ($this->filterSet as $filter) {
            $list[] = new FilterView($filter);
        }
        return $list;
    }

    public function generateUrlForSelect(FilterSetElementValue $value): string
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

        return Router::urlToCars($this->catalogId(), $this->modelId(), $state->toString());
    }


    public function generateUrlForReset(FilterView $filterView): string
    {
        $idxList   = [];
        $filterKey = $filterView->key();
        $fs        = $this->filterSet;

        /** @var FilterSetElement $element */
        foreach ($fs as $element) {
            if ($element->key == $filterKey) {
                continue;
            }
            $idxList[] = $element->getSelectedValueIdx();
        }
        $state = new FilterSetState($idxList);

        return Router::urlToCars($this->catalogId(), $this->modelId(), $state->toString());
    }

    public function filterState(): string
    {
        $idxList = [];
        /** @var FilterSetElement $fsElement */
        foreach ($this->filterSet as $fsElement) {
            $idxList[] = $fsElement->getSelectedValueIdx();
        }
        $state = new FilterSetState($idxList);

        return $state->toString();
    }

    /**
     * Return count of enabled filters
     *
     * @return int
     */
    public function filtersEnabled(): int
    {
        return $this->filterSet->selectedFilterCount();
    }

    public function carParameterName($carParameterKey): string
    {
        return $this->filterSet->getFilterByKey($carParameterKey)->name;
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

    /**
     * Get url to filters page
     *
     * @return string
     */
    public function urlToFilters(): string
    {
        return Router::urlToFilters($this->catalogId(), $this->modelId(), $this->filterState());
    }

    /**
     * Get url to groups
     *
     * @param string $carId
     * @param string $criteria
     *
     * @return string
     */
    public function urlToGroups(string $carId, string $criteria): string
    {
        return Router::urlToGroups($this->catalogId(), $carId, "", $criteria);
    }

    /**
     * Return url to select region
     *
     * @param string|null $region
     *
     * @return string
     */
    public function selectRegionUrl(?string $region): string
    {
        return $this->selectFilterUrl(CarParameter::KEY_SALES_REGION, $region);
    }

    /**
     * @param string|null $region
     *
     * @return string
     */
    public function selectSteeringUrl(?string $region): string
    {
        return $this->selectFilterUrl(CarParameter::KEY_STEERING, $region);
    }

    /**
     * @param string|null $year
     *
     * @return string
     */
    public function selectYearUrl(?string $year): string
    {
        return $this->selectFilterUrl(CarParameter::KEY_YEAR, $year);
    }

    public function selectTransmissionTypeUrl(?string $transmissionType): string
    {
        return $this->selectFilterUrl(CarParameter::KEY_TRANSMISSION_TYPE, $transmissionType);
    }

    /**
     * @param string|null $engine
     *
     * @return string
     */
    public function selectEngineUrl(?string $engine): string
    {
        return $this->selectFilterUrl(CarParameter::KEY_ENGINE, $engine);
    }

    private function selectFilterUrl(string $carParameterKey, ?string $value): string
    {
        $idxList = [];
        /** @var FilterSetElement $fsElement */
        foreach ($this->filterSet as $fsElement) {
            if ($fsElement->key == $carParameterKey) {
                foreach ($fsElement->getValues() as $fsElementValue) {
                    if ($fsElementValue->getValue() == $value) {
                        $idxList[] = $fsElementValue->getIdx();
                    }
                }
                continue;
            }

            $idxList[] = $fsElement->getSelectedValueIdx();
        }

        $state = new FilterSetState($idxList);

        return Router::urlToCars($this->catalogId(), $this->modelId(), $state->toString());
    }
}
