<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

use PartsCatalogsClient\PcModel;

class Model extends PcModel
{
    public $id;
    public $name;
    public $img;
    /**
     * @param $array
     * @return Model
     */
    public static function fromArray($array): PcModel
    {
        $obj =  new Model();
        $obj->id   = $array['id'];
        $obj->name = $array['name'];
        $obj->img  = $array['img'];
        return $obj;
    }
}
