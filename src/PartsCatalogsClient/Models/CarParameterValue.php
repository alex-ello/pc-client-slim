<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class CarParameterValue
{
    public $idx;
    public $value;

    public function __construct(string $idx, string $value)
    {
        $this->idx   = $idx;
        $this->value = $value;
    }


    /**
     * @param $array
     *
     * @return CarParameterValue
     */
    public static function fromArray($array): CarParameterValue
    {
        return new CarParameterValue($array['idx'], $array['value']);
    }

    public function toArray(): array
    {
        return [
            'idx' => $this->idx,
            'value' => $this->value,
        ];
    }
}
