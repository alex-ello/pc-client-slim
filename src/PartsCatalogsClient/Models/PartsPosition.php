<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class PartsPosition
{
    public $number;
    public $x;
    public $y;
    public $w;
    public $h;

    public function __construct($number, $x, $y, $w, $h)
    {
        $this->number = $number;
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }

    public static function fromArray($array): PartsPosition
    {
        $c = $array['coordinates'];
        return new PartsPosition($array['number'], $c[0], $c[1], $c[2], $c[3]);
    }
}
