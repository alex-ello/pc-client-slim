<?php declare(strict_types=1);

namespace PartsCatalogsSlim;

use PartsCatalogsSlim\Controller\CarsController;
use PartsCatalogsSlim\Controller\CatalogsController;
use PartsCatalogsSlim\Controller\GroupsController;
use PartsCatalogsSlim\Controller\ModelsController;
use PartsCatalogsSlim\Controller\PartsController;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    const BASE_PATH       = '';
    const CATALOGS_ACTION = CatalogsController::class . ':catalogsAction';
    const MODELS_ACTION   = ModelsController::class . ':modelsAction';
    const CARS_ACTION     = CarsController::class . ':carsAction';
    const VIN_ACTION      = CarsController::class . ':vinAction';
    const FILTERS_ACTION  = CarsController::class . ':filtersAction';
    const GROUPS_ACTION   = GroupsController::class . ':groupsAction';
    const PARTS_ACTION    = PartsController::class . ':partsAction';
    const SCHEMA_ACTION   = PartsController::class . ':schemaAction';

    const QUERY_PARAM_STATE      = 'state';
    const QUERY_PARAM_CAR_ID     = 'carlId';
    const QUERY_PARAM_MODEL_ID   = 'modelId';
    const QUERY_PARAM_VIN        = 'vin';
    const QUERY_PARAM_GROUP_PATH = 'gp';
    const QUERY_PARAM_CRITERIA   = 'c';
    const QUERY_PARAM_CATALOG_ID = 'catalogId';

    public static function urlToCatalogs()
    {
        return sprintf("%s/", self::BASE_PATH);
    }

    public static function urlToModels(string $catalogId): string
    {
        return sprintf("%s/catalogs/%s/models/", self::BASE_PATH, $catalogId);
    }

    public static function urlToCars(string $catalogId, string $modelId, string $state = ""): string
    {
        $q = http_build_query([
            self::QUERY_PARAM_MODEL_ID => $modelId,
            self::QUERY_PARAM_STATE => $state,
        ]);
        return sprintf("%s/catalogs/%s/cars/?%s", self::BASE_PATH, $catalogId, $q);
    }

    public static function urlToFilters($catalogId, $modelId, $state): string
    {
        $q = http_build_query([
            self::QUERY_PARAM_MODEL_ID => $modelId,
            self::QUERY_PARAM_STATE => $state,
        ]);
        return sprintf("%s/catalogs/%s/filters/?%s", self::BASE_PATH, $catalogId, $q);
    }

    public static function urlToGroups(string $catalogId, string $carId, string $groupPath = "", string $criteria = ""): string
    {
        $q = http_build_query([
            self::QUERY_PARAM_CAR_ID => $carId,
            self::QUERY_PARAM_GROUP_PATH => $groupPath,
            self::QUERY_PARAM_CRITERIA => $criteria,
        ]);
        return sprintf("%s/catalogs/%s/groups/?%s", self::BASE_PATH, $catalogId, $q);
    }

    public static function urlToParts(string $catalogId, string $carId, string $groupPath = "", string $criteria = "")
    {
        $q = http_build_query([
            self::QUERY_PARAM_CAR_ID => $carId,
            self::QUERY_PARAM_GROUP_PATH => $groupPath,
            self::QUERY_PARAM_CRITERIA => $criteria,
        ]);
        return sprintf("%s/catalogs/%s/parts/?%s", self::BASE_PATH, $catalogId, $q);
    }

    public static function urlToSchema($catalogId, $carId, $groupPath, $criteria = "")
    {
        $q = http_build_query([
            self::QUERY_PARAM_CAR_ID => $carId,
            self::QUERY_PARAM_GROUP_PATH => $groupPath,
            self::QUERY_PARAM_CRITERIA => $criteria,
        ]);
        return sprintf("%s/catalogs/%s/schema/?%s", self::BASE_PATH, $catalogId, $q);
    }

    public static function urlToVin()
    {
        return sprintf("%s/vin/", self::BASE_PATH);
    }

    public static function getCarId(ServerRequestInterface $request, array $args): ?string
    {
        $q = $request->getQueryParams();
        if (isset($q[self::QUERY_PARAM_CAR_ID])) {
            return $q[self::QUERY_PARAM_CAR_ID];
        }
        return "";
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @param array                  $args
     *
     * @return string
     */
    public static function getVin(ServerRequestInterface $request, array $args): string
    {
        $queryParams = $request->getQueryParams();
        return isset($queryParams[Router::QUERY_PARAM_VIN]) ? $queryParams[Router::QUERY_PARAM_VIN] : '';
    }

    public static function getCriteria(ServerRequestInterface $request, array $args)
    {
        $queryParams = $request->getQueryParams();
        return isset($queryParams[Router::QUERY_PARAM_CRITERIA]) ? $queryParams[Router::QUERY_PARAM_CRITERIA] : '';
    }

    public static function getCatalogId(ServerRequestInterface $request, array $args): string
    {
        return isset($args[Router::QUERY_PARAM_CATALOG_ID]) ? $args[Router::QUERY_PARAM_CATALOG_ID] : '';
    }

    public static function getModelId(ServerRequestInterface $request, array $args): string
    {
        $queryParams = $request->getQueryParams();
        return isset($queryParams[Router::QUERY_PARAM_MODEL_ID]) ? $queryParams[Router::QUERY_PARAM_MODEL_ID] : '';
    }

    public static function getFilterState(ServerRequestInterface $request, array $args): string
    {
        $queryParams = $request->getQueryParams();
        return isset($queryParams[Router::QUERY_PARAM_STATE]) ? $queryParams[Router::QUERY_PARAM_STATE] : '';
    }

    public static function getGroupPath(ServerRequestInterface $request, array $args): string
    {
        $queryParams = $request->getQueryParams();
        return isset($queryParams[Router::QUERY_PARAM_GROUP_PATH]) ? $queryParams[Router::QUERY_PARAM_GROUP_PATH] : '';
    }
}
