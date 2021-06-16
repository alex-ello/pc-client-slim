<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\CatalogCollection;
use PartsCatalogsSlim\Router;

class CatalogsView extends LayoutView
{
    /**
     * @var CatalogCollection|Catalog[]
     */
    public $catalogs;

    public function __construct(CatalogCollection $catalogs)
    {
        $this->catalogs = $catalogs;
    }

    /**
     * Catalogs grouped by first letter
     *
     * @return iterable|Catalog[]
     */
    public function groupedCatalogs(): iterable
    {
        return $this->catalogs->sortAndGroup(
            function (Catalog $a, Catalog $b) {
                return $a->name <=> $b->name;
            },
            function (Catalog $catalog) {
                return $catalog->name[0];
            }
        );
    }

    /**
     * Get url to models page
     *
     * @param string $catalogId
     *
     * @return string
     */
    public function urlToModels(string $catalogId): string
    {
        return Router::urlToModels($catalogId);
    }
}
