<?php

namespace PartsCatalogsSlim;

use Exception;

class Vin
{
    /**
     * @var string
     */
    private $vin;

    public function __construct(string $vin)
    {
        if (!Vin::isValid($vin)) {
            throw new Exception('Vin number not valid');
        }
        $this->vin = strtoupper($vin);
    }

    public static function isValid(string $vin): bool
    {
        // validate Vin
        if (preg_match('/[a-z0-9]{17}/i', $vin)) {
            return true;
        }
        // Validate Frame
        if (preg_match('/([a-z0-9]+-[a-z0-9]+){4,17}/i', $vin)) {
            return true;
        }

        return false;
    }


    public function __toString(): string
    {
        return $this->vin;
    }

    public function toString(): string
    {
        return $this->vin;
    }
}
