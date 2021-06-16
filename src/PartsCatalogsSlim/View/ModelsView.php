<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsClient\Models\ModelCollection;
use PartsCatalogsSlim\Router;

class ModelsView extends LayoutView
{
    /**
     * @var Catalog
     */
    public $catalog;

    /**
     * @var ModelCollection|Model[]
     */
    public $models;

    public function __construct(Catalog $catalog, ModelCollection $models)
    {
        $this->catalog = $catalog;
        $this->models  = $models;
    }
    /**
     * Return list of Model grouped by name
     *
     * @return array|Model[]
     */
    public function groupedModels(): array
    {
        return $this->models->sortAndGroup(function (Model $a, Model $b) {
            return $a->name <=> $b->name;
        }, function (Model $a) {
            return $a->name[0];
        });
    }

    /**
     * Catalog id
     *
     * @return string
     */
    public function catalogId(): string
    {
        return $this->catalog->id;
    }

    /**
     * Catalog name
     * @return mixed
     */
    public function catalogName()
    {
        return $this->catalog->name;
    }

    /**
     * Get url to cars page
     *
     * @param string $modelId
     *
     * @return string
     */
    public function urlToCars(string $modelId): string
    {
        return Router::urlToCars($this->catalogId(), $modelId);
    }
}
