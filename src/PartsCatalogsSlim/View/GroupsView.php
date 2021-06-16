<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\Group;
use PartsCatalogsClient\Models\GroupCollection;
use PartsCatalogsClient\Models\NameString;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\CarView;
use PartsCatalogsSlim\View\GroupsView\GroupView;

class GroupsView extends LayoutView
{

    /**
     * @var Group|null
     */
    public $currentGroup;
    /**
     * @var Catalog|null
     */
    public $catalog;

    /**
     * @var GroupCollection|Group[]
     */
    public $groups;

    public function __construct(Catalog $catalog, CarView $carView, ?Group $currentGroup, GroupCollection $groups)
    {
        $this->catalog      = $catalog;
        $this->currentGroup = $currentGroup;
        $this->groups       = $groups;

        $this->setClientCar($carView);
    }

    public function currentGroupName(): string
    {
        if ($this->currentGroup !== null) {
            return NameString::beautify($this->currentGroup->name);
        }
        return '';
    }

    public function catalogId(): string
    {
        return $this->catalog->id;
    }

    public function catalogName(): string
    {
        return $this->catalog->name;
    }

    public function carId()
    {
        return $this->getClientCar()->id;
    }

    public function modelName(): string
    {
        return $this->getClientCar()->modelName;
    }

    public function modelId(): string
    {
        return $this->getClientCar()->modelId;
    }

    public function isRoot(): bool
    {
        return !isset($this->currentGroup);
    }

    /**
     * @return GroupView[]
     */
    public function groups(): iterable
    {
        return array_map(function (Group $group) {
            return new GroupView($group);
        }, iterator_to_array($this->groups));
    }

    /**
     * Return url to groups or parts
     *
     * @param GroupView $groupView
     *
     * @return string
     */
    public function urlToGroupOrParts(GroupView $groupView): string
    {
        $catalogId = $this->catalogId();
        $clientCar = $this->getClientCar();
        $carId     = $clientCar->id();
        $criteria  = $clientCar->criteria();

        $groupPath = $groupView->path()->toString();

        if ($groupView->hasParts()) {
            return Router::urlToParts($catalogId, $carId, $groupPath, $criteria);
        }

        return Router::urlToGroups($catalogId, $carId, $groupPath, $criteria);
    }

    /**
     * Get url to models page
     *
     * @return string
     */
    public function urlToModels(): string
    {
        return Router::urlToModels($this->catalogId());
    }

    /**
     * Get url to cars page
     *
     * @return string
     */
    public function urlToCars(): string
    {
        return Router::urlToCars($this->catalogId(), $this->modelId());
    }

    /**
     * Get url to groups
     *
     * @return string
     */
    public function urlToGroups(): string
    {
        return Router::urlToGroups($this->catalogId(), $this->carId());
    }
}
