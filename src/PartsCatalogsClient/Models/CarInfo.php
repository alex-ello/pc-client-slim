<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarInfo
{
    public $brand;
    public $carId;
    public $catalogId;
    public $criteria;
    public $modelId;
    public $modelName;
    public $title;

    /**
     * @var OptionCode[]
     */
    public $optionCodes = [];

    /**
     * @var CarParameters|CarParameter[]
     */
    public $parameters;

    /**
     * @var string
     */
    public $vin;

    /**
     * @var string
     */
    public $frame;

    /**
     * @param array $array
     *
     * @return CarInfo
     */
    public static function fromArray($array): CarInfo
    {
        $obj            = new CarInfo();
        $obj->brand     = $array['brand'];
        $obj->carId     = $array['carId'];
        $obj->catalogId = $array['catalogId'];
        $obj->criteria  = $array['criteria'];
        $obj->frame     = $array['frame'];
        $obj->modelId   = $array['modelId'];
        $obj->modelName = $array['modelName'];
        $obj->title     = $array['title'];
        $obj->vin       = $array['vin'];

        foreach ($array['optionCodes'] as $optionCodeArr) {
            $obj->optionCodes[] = new OptionCode($optionCodeArr['code'], $optionCodeArr['description']);
        }
        $obj->parameters = CarParameters::fromArray($array['parameters']);

        return $obj;
    }

    public function getYear(): ?string
    {
        return $this->parameters->getValue(CarParameter::KEY_YEAR);
    }

    public function getSteering()
    {
        return $this->parameters->getValue(CarParameter::KEY_STEERING);
    }

    public function getEngine()
    {
        return $this->firstValue(CarParameter::KEY_ENGINE);
    }

    public function getEngineCode()
    {
        return $this->firstValue(CarParameter::KEY_ENGINE_CODE);
    }

    public function getTransmission()
    {
        return $this->firstValue(CarParameter::KEY_TRANSMISSION);
    }

    public function getTransmissionType()
    {
        return $this->parameters->getValue(CarParameter::KEY_TRANSMISSION_TYPE);
    }

    public function getBodyType()
    {
        return $this->parameters->getValue(CarParameter::KEY_BODY_TYPE);
    }

    public function getBody()
    {
        return $this->parameters->getValue(CarParameter::KEY_BODY);
    }

    /**
     * Get vin or frame number
     *
     * @return string
     */
    public function getVinFrame(): string
    {
        if ($this->vin) {
            return $this->vin;
        }
        return $this->frame;
    }

    private function firstValue(...$keys)
    {
        foreach ($keys as $key) {
            if ($this->parameters->has($key)) {
                return $this->parameters->getValue($key);
            }
        }
        return null;
    }
}
