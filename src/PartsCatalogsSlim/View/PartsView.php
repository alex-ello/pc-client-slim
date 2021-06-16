<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Car;
use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\Group;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsClient\Models\NameString;
use PartsCatalogsClient\Models\PartsGroup;
use PartsCatalogsClient\Models\PartsPosition;
use PartsCatalogsClient\Models\SchemeAndParts;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\CarView;
use PartsCatalogsSlim\View\PartsView\PartsGroupView;

class PartsView extends LayoutView
{

    /**
     * @var Catalog|null
     */
    public $catalog;

    /**
     * @var Group|null
     */
    public $group;
    /**
     * @var SchemeAndParts
     */
    public $schemeAndParts;

    public function __construct(Catalog $catalog, CarView $carView, Group $group, SchemeAndParts $schemeAndParts)
    {
        $this->catalog        = $catalog;
        $this->group          = $group;
        $this->schemeAndParts = $schemeAndParts;

        $this->setClientCar($carView);
    }

    public function catalogId(): string
    {
        return $this->catalog->id;
    }

    public function catalogName()
    {
        return $this->catalog->name;
    }

    public function modelName(): string
    {
        return $this->getClientCar()->modelName;
    }

    public function carId(): string
    {
        return $this->getClientCar()->id;
    }

    /**
     * @return PartsGroupView[]
     */
    public function partsGroups(): array
    {
        return array_map(function (PartsGroup $pg) {
            return new PartsGroupView($pg);
        }, $this->schemeAndParts->partsGroups);
    }

    /**
     * Return list of PartsPosition grouped by positionNumber
     *
     * @return array|iterable[]
     */
    public function partsPositionsGrouped(): iterable
    {
        $list = [];
        foreach ($this->schemeAndParts->partsPositions as $partPosition) {
            $list[$partPosition->number][] = $partPosition;
        }
        return $list;
    }

    public function imgUrl(): string
    {
        return (string)$this->schemeAndParts->img;
    }

    public function groupPath(): string
    {
        return $this->group->getPath()->toString();
    }

    public function currentGroupName()
    {
        return NameString::beautify($this->group->name);
    }

    public function searchUrl($code, $priceUrlTemplate)
    {
        $url = str_replace('{code}', $code, $priceUrlTemplate);
        $url = str_replace('{brand}', $this->catalogId(), $url);

        return $url;
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
        $modelId = $this->getClientCar()->modelId;

        return Router::urlToCars($this->catalogId(), $modelId);
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

    /**
     * Get url to parts
     *
     * @return string
     */
    public function urlToParts(): string
    {
        return Router::urlToParts($this->catalogId(), $this->carId(), $this->groupPath());
    }

    /**
     * Url to schema
     *
     * @return string
     */
    public function urlToSchema(): string
    {
        return Router::urlToSchema($this->catalogId(), $this->carId(), $this->groupPath());
    }
}
