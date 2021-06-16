<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View\GroupsView;

use PartsCatalogsClient\GroupPath;
use PartsCatalogsClient\Models\Group;
use PartsCatalogsClient\Models\NameString;

class GroupView
{
    /**
     * @var Group
     */
    public $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function img(): string
    {
        return (string) $this->group->img;
    }

    public function hasParts(): bool
    {
        return $this->group->hasParts;
    }

    public function name()
    {
        return NameString::beautify($this->group->name);
    }

    public function description(): string
    {
        $description = (string)$this->group->description;
        $description = preg_replace("/\s+\n/s", "\n", $description);
        return $description;
    }

    public function path(): GroupPath
    {
        return $this->group->getPath();
    }
}
