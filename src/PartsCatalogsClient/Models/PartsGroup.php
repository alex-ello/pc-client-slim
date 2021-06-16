<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class PartsGroup
{
    /**
     * @var Part[]
     */
    public $parts = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $positionNumber;

    /**
     * @var string
     */
    public $description;

    public static function fromArray(array $array)
    {
        $obj                 = new PartsGroup();
        $obj->name           = $array['name'];
        $obj->number         = $array['number'];
        $obj->positionNumber = $array['positionNumber'];
        $obj->description    = $array['description'];
        foreach ($array['parts'] as $part) {
            $obj->parts[] = Part::fromArray($part);
        }
        return $obj;
    }
}
