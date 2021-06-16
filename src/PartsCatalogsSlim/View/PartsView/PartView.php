<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View\PartsView;

use PartsCatalogsClient\Models\NameString;
use PartsCatalogsClient\Models\Part;

class PartView
{
    /**
     * @var Part
     */
    public $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function number(): string
    {
        return $this->part->number;
    }

    public function hasNumber(): bool
    {
        return !empty($this->part->number);
    }

    public function notice(): string
    {
        return $this->part->notice;
    }

    public function hasNotice(): bool
    {
        return !empty(trim($this->part->notice));
    }
    
    public function name(): string
    {
        return NameString::beautify($this->part->name);
    }

    public function description(): string
    {
        return $this->part->description;
    }

    public function hasDescription(): bool
    {
        return !empty(trim($this->part->description));
    }
}
