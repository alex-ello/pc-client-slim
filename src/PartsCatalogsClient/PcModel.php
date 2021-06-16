<?php declare(strict_types=1);

namespace PartsCatalogsClient;

abstract class PcModel
{
    abstract public static function fromArray($array): PcModel;
}
