<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class NameString
{
    public static function beautify($name)
    {
        $name = preg_replace("/\s*([\/&])\s*/i", " $1 ", $name);
        $name = preg_replace("/(,)(?=\w)/i", "$1 ", $name);
        $name = mb_strtolower($name, 'UTF-8');
        $name = preg_replace_callback("/(^\w|\/ \w|& \w)/", function ($f) {
            return mb_strtoupper($f[1], 'UTF-8');
        }, $name);
        $name = preg_replace_callback("/(abs|abr|esa|asr|esp)/i", function ($f) {
            return mb_strtoupper($f[1], 'UTF-8');
        }, $name);

        return $name;
    }
}
