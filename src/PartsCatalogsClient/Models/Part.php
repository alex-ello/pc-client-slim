<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class Part
{
    public $id = '';
    public $number = '';
    public $name = '';
    public $notice = '';
    public $description = '';
    public $positionNumber = '';
    public $url = '';

    public static function fromArray($array)
    {
        $obj = new Part();

        $obj->id             = (string)$array['id'];
        $obj->number         = (string)$array['number'];
        $obj->name           = (string)$array['name'];
        $obj->notice         = (string)$array['notice'];
        $obj->description    = (string)$array['description'];
        $obj->positionNumber = (string)$array['positionNumber'];
        $obj->url            = (string)$array['url'];

        return $obj;
    }
}
