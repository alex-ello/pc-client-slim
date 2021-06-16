<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\CarInfo;
use PartsCatalogsClient\Models\CarParameter;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\CarView;
use PartsCatalogsSlim\Vin;

class VinView extends LayoutView
{
    /**
     * @var CarView[]
     */
    public $cars = [];

    /**
     * @var bool
     */
    private $has;

    /**
     * @var Vin
     */
    private $vin;


    /**
     * @var string|null
     */
    private $error;

    private $notFound = false;

    /**
     * @param $cars
     *
     * @return $this
     */
    public function setCars($cars)
    {
        foreach ($cars as $carInfo) {
            $carView = CarView::fromCarInfo($carInfo);

            foreach ($carInfo->parameters as $parameter) {
                if (!isset($this->has[$parameter->key])) {
                    $this->has[$parameter->key] = true;
                }
            }
            $this->cars[] = $carView;
        }
        return $this;
    }

    public function setVin(Vin $vin)
    {
        $this->vin = $vin;
    }


    /**
     * Return list of CarView grouped by brand
     *
     * @return array|CarView[]
     */
    public function carsGroupedByBrand(): array
    {
        $groups = [];

        foreach ($this->cars as $carView) {
            $groups[$carView->brand][] = $carView;
        }
        return $groups;
    }

    public function hasRegion(): bool
    {
        return $this->hasParameter(CarParameter::KEY_SALES_REGION);
    }

    public function hasSteering(): bool
    {
        return $this->hasParameter(CarParameter::KEY_STEERING);
    }

    public function hasYear(): bool
    {
        return $this->hasParameter(CarParameter::KEY_YEAR);
    }

    public function hasEngine(): bool
    {
        if ($this->hasParameter(CarParameter::KEY_ENGINE)) {
            return true;
        }
        if ($this->hasParameter(CarParameter::KEY_ENGINE_CODE)) {
            return true;
        }
        return false;
    }

    public function hasTransmissionSimple(): bool
    {
        if ($this->hasParameter(CarParameter::KEY_TRANSMISSION)) {
            return true;
        }
        if ($this->hasParameter(CarParameter::KEY_TRANSMISSION_TYPE)) {
            return true;
        }
        return false;
    }

    public function hasParameter(string $key): bool
    {
        if (isset($this->has[$key])) {
            return $this->has[$key];
        }
        return false;
    }

    public function hasResults(): bool
    {
        return !empty($this->cars);
    }

    /**
     * Vin
     *
     * @return string
     */
    public function vin(): string
    {
        if ($this->vin) {
            return $this->vin->toString();
        }
        return '';
    }

    /**
     * Get url to groups
     *
     * @param CarView $carView
     *
     * @return string
     */
    public function urlToGroups(CarView $carView): string
    {
        return Router::urlToGroups($carView->catalogId, $carView->id, "", $carView->criteria);
    }

    public function urlToVin()
    {
        return Router::urlToVin();
    }

    public function setError(string $string)
    {
        $this->error = $string;
        return $this;
    }

    public function hasError()
    {
        return $this->error !== null;
    }

    public function error()
    {
        return $this->error;
    }

    public function ifNotFound(): bool
    {
        if ($this->vin && count($this->cars) == 0) {
            return true;
        }
        return false;
    }
}
