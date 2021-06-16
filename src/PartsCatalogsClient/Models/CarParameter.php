<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarParameter
{
    const KEY_YEAR = 'year';
    const KEY_SALES_REGION = 'sales_region';
    const KEY_STEERING = 'steering';
    const KEY_TRANSMISSION = 'transmission';
    const KEY_TRANSMISSION_CODE = 'trans_code';
    const KEY_TRANSMISSION_TYPE = 'trans_type';
    const KEY_ENGINE = 'engine';
    const KEY_ENGINE_CODE = 'engine_code';
    const KEY_BODY_TYPE = 'body_type';
    const KEY_BODY = 'body';


    public $key;
    public $name;
    public $value;

    /**
     * @var CarParameterValue[]
     */
    public $values;

    private function __construct()
    {
    }

    public static function fromArray($array)
    {
        $obj        = new CarParameter();
        $obj->key   = $array['key'];
        $obj->name  = $array['name'];
        $obj->value = $array['value'];

        return $obj;
    }
}
