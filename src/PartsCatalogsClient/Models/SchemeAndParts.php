<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class SchemeAndParts
{

    public $img = '';
    public $imgDescription = '';
    /**
     * @var PartsGroup[]
     */
    public $partsGroups = [];

    /**
     * @var PartsPosition[]
     */
    public $partsPositions = [];


    /**
     * @param $array
     * @return SchemeAndParts
     */
    public static function fromArray($array)
    {
        $obj                 = new SchemeAndParts();
        $obj->img            = $array['img'];
        $obj->imgDescription = $array['imgDescription'];

        foreach ($array['partGroups'] as $partsGroupArr) {
            $obj->partsGroups[] = PartsGroup::fromArray($partsGroupArr);
        }
        foreach ($array['positions'] as $position) {
            $obj->partsPositions[] = PartsPosition::fromArray($position);
        }

        return $obj;
    }
}
