<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View\PartsView;

use PartsCatalogsClient\Models\NameString;
use PartsCatalogsClient\Models\Part;
use PartsCatalogsClient\Models\PartsGroup;

class PartsGroupView
{
    /**
     * @var PartsGroup
     */
    private $partsGroup;

    /**
     * @var string
     */
    private $positionNumber;

    public function __construct(PartsGroup $partsGroup)
    {
        $this->partsGroup     = $partsGroup;
        $this->positionNumber = $partsGroup->positionNumber;
    }

    public function name()
    {
        return NameString::beautify($this->partsGroup->name);
    }

    public function description()
    {
        return $this->partsGroup->description;
    }

    /**
     * Return list of PartView grouped by positionNumber
     *
     * @return PartView[]
     */
    public function partsGrouped(): array
    {
        $list = [];
        foreach ($this->partsGroup->parts as $part) {
            $list[$part->positionNumber][] = new PartView($part);
        }

        return $list;
    }
}
