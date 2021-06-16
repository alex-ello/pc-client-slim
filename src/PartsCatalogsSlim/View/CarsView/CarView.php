<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View\CarsView;

use PartsCatalogsClient\Models\Car;
use PartsCatalogsClient\Models\CarInfo;
use PartsCatalogsClient\Models\CarParameter;
use PartsCatalogsClient\Models\CarParameters;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsClient\Models\OptionCode;
use PartsCatalogsSlim\View\CarsView;

class CarView
{
    /**
     * @var string
     */
    public $catalogId;
    public $id = '';
    public $name;
    public $modelId;
    public $modelName = '';
    public $description;
    public $brand;
    public $vin;
    public $frame;

    public $criteria = '';


    /**
     * @var OptionCode[]
     */
    public $optionCodes = [];

    /**
     * @var CarParameters
     */
    public $parameters;


    public static function fromCar(Car $car): CarView
    {
        $view              = new CarView();
        $view->id          = $car->id;
        $view->brand       = $car->brand;
        $view->modelId     = $car->modelId;
        $view->modelName   = $car->modelName;
        $view->name        = $car->name;
        $view->parameters  = $car->parameters;
        $view->description = $car->description;
        $view->catalogId   = $car->catalogId;
        $view->vin         = $car->vin;
        $view->frame       = $car->frame;
        $view->criteria    = $car->criteria;

        return $view;
    }

    public static function fromCarInfo(CarInfo $carInfo): CarView
    {
        $view              = new CarView();
        $view->id          = $carInfo->carId;
        $view->brand       = $carInfo->brand;
        $view->modelId     = $carInfo->modelId;
        $view->modelName   = $carInfo->modelName;
        $view->name        = $carInfo->title;
        $view->parameters  = $carInfo->parameters;
        $view->catalogId   = $carInfo->catalogId;
        $view->criteria    = $carInfo->criteria;
        $view->optionCodes = $carInfo->optionCodes;
        $view->vin         = $carInfo->vin;
        $view->frame       = $carInfo->frame;

        return $view;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function brand(): string
    {
        return ucfirst($this->brand);
    }

    public function name()
    {
        return $this->name;
    }

    /**
     * Show steering and body information
     *
     * @return string
     */
    public function bodySummary(): string
    {
        $params = array_filter([$this->bodyType(), $this->body()]);
        return implode(" - ", $params);
    }

    public function steering(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_STEERING);
    }

    public function hasSteering(): bool
    {
        return $this->parameters->has(CarParameter::KEY_STEERING);
    }

    public function bodyType(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_BODY_TYPE);
    }

    public function body(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_BODY);
    }

    public function region()
    {
        return $this->parameters->getValue(CarParameter::KEY_SALES_REGION);
    }

    public function hasRegion(): bool
    {
        return $this->parameters->has(CarParameter::KEY_SALES_REGION);
    }

    public function year(): string
    {
        return (string)$this->parameters->getValue(CarParameter::KEY_YEAR);
    }

    public function hasYear()
    {
        return $this->parameters->has(CarParameter::KEY_YEAR);
    }

    public function engine(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_ENGINE);
    }

    public function hasEngine(): bool
    {
        return $this->parameters->has(CarParameter::KEY_ENGINE);
    }

    public function engineCode(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_ENGINE_CODE);
    }

    public function transmissionType()
    {
        return $this->parameters->getValue(CarParameter::KEY_TRANSMISSION_TYPE);
    }

    public function transmission(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_TRANSMISSION);
    }

    public function hasTransmission(): bool
    {
        return $this->parameters->has(CarParameter::KEY_TRANSMISSION);
    }

    public function hasTransmissionType(): bool
    {
        return $this->parameters->has(CarParameter::KEY_TRANSMISSION_TYPE);
    }

    public function description()
    {
        return $this->description;
    }

    public function hasDescription()
    {
        return !empty($this->description);
    }

    /**
     * Return true if car has parameters
     *
     * @return bool
     */
    public function hasSecondaryParams(): bool
    {
        return !empty($this->secondaryParams());
    }

    public function criteria(): string
    {
        return (string) $this->criteria;
    }

    /**
     * @return OptionCode[]
     */
    public function optionCodes(): iterable
    {
        return $this->optionCodes;
    }

    public function hasOptionCodes(): bool
    {
        return !empty($this->optionCodes);
    }

    public function vin(): string
    {
        return (string)$this->vin;
    }

    public function hasVin(): bool
    {
        return (bool)$this->vin;
    }

    public function frame(): string
    {
        return $this->frame;
    }

    public function hasFrame(): bool
    {
        return (bool)$this->frame;
    }

    /**
     * @return CarParameter[]
     */
    public function secondaryParams(): iterable
    {
        $list = [];
        foreach ($this->parameters as $parameter) {
            if (in_array($parameter->key, CarsView::PRIMARY_PARAMS)) {
                continue;
            }
            $list[] = $parameter;
        }
        return $list;
    }

    /**
     * @return string
     */
    public function transmissionTypeSimple(): string
    {
        $t  = $this->parameters->getValue(CarParameter::KEY_TRANSMISSION);
        $tt = $this->parameters->getValue(CarParameter::KEY_TRANSMISSION_TYPE);

        if ($t && $tt) {
            return $tt . " <small>(" . $t . ")</small>";
        }
        if ($t) {
            return $t;
        }

        if ($tt) {
            return $tt;
        }

        return "";
    }
}
