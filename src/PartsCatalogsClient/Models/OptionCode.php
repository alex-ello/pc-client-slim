<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

class OptionCode
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $description;

    public function __construct(string $code, string $description)
    {
        $this->code        = $code;
        $this->description = $description;
    }
}
