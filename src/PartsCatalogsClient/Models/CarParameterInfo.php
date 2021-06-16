<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarParameterInfo
{
    public $key;
    public $name;
    /**
     * @var CarParameterValue[]
     */
    public $values;
    public $sortOrder;

    public function __construct(string $key, string $name, int $sortOrder = 0)
    {
        $this->key       = $key;
        $this->name      = $name;
        $this->sortOrder = $sortOrder;
    }


    public static function fromArray($array): CarParameterInfo
    {
        $obj            = new CarParameterInfo($array['key'], $array['name'], $array['sortOrder']);

        foreach ($array['values'] as $valueArr) {
            $cpv           = CarParameterValue::fromArray($valueArr);
            $obj->values[] = $cpv;
        }

        return $obj;
    }

    /**
     * @param CarParameterValue $value
     *
     * @return CarParameterInfo
     */
    public function addValue(CarParameterValue $value): CarParameterInfo
    {
        $this->values[] = $value;

        return $this;
    }

    public function toArray(): array
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = $value->toArray();
        }
        return [
            'key' => $this->key,
            'name' => $this->name,
            'sortOrder' => $this->sortOrder,
            'values' => $values,
        ];
    }
}
