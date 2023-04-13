<?php declare(strict_types=1);

use PartsCatalogsSlim\Router;
use Slim\App;

/**
 * @param App $app
 */
return function (App $app) {

    $app->get('/', Router::CATALOGS_ACTION);
    $app->get('/vin/', Router::VIN_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/models/', Router::MODELS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/model/{modelId}/cars', Router::MODELS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/cars/', Router::CARS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/filters/', Router::FILTERS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/groups/', Router::GROUPS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/parts/', Router::PARTS_ACTION);
    $app->get('/catalogs/{' . Router::QUERY_PARAM_CATALOG_ID. '}/schema/', Router::SCHEMA_ACTION);
};
