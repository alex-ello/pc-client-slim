<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class Car
{
    public $id;
    public $catalogId;
    public $name;
    public $modelId;
    public $vin;
    public $frame;
    public $criteria;
    public $description;
    public $brand;
    public $modelName;

    /**
     * @var CarParameters
     */
    public  $parameters;

    /**
     * @param array $array
     * @return Car
     */
    public static function fromArray($array)
    {
        $obj              = new Car();
        $obj->id          = $array['id'];
        $obj->catalogId   = $array['catalogId'];
        $obj->brand       = $array['brand'];
        $obj->name        = $array['name'];
        $obj->description = $array['description'];
        $obj->modelId     = $array['modelId'];
        $obj->modelName   = $array['modelName'];

        if ($array['vin']) {
            $obj->vin = $array['vin'];
        }
        if ($array['frame']) {
            $obj->frame = $array['frame'];
        }
        if ($array['criteria']) {
            $obj->criteria = $array['criteria'];
        }
        $obj->parameters  = CarParameters::fromArray($array['parameters']);

        return $obj;
    }
}
