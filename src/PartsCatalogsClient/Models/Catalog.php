<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class Catalog
{
    public $id;
    public $name;
    public $description;

    /**
     * @param $array
     * @return Catalog
     */
    public static function fromArray($array)
    {
        $obj =  new Catalog();
        $obj->id   = $array['id'];
        $obj->name = $array['name'];
        return $obj;
    }
}
