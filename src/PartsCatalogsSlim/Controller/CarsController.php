<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\ClientException;
use PartsCatalogsSlim\FilterSet;
use PartsCatalogsSlim\FilterSetState;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView;
use PartsCatalogsSlim\View\FiltersView;
use PartsCatalogsSlim\View\VinView;
use PartsCatalogsSlim\Vin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CarsController extends AbstractController
{
    /**
     * Show list of available cars
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function carsAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $pcClient = $this->getClient();

        $catalogId         = Router::getCatalogId($request, $args);
        $modelId           = Router::getModelId($request, $args);
        $filterSetStateStr = Router::getFilterState($request, $args);


        $catalog   = $pcClient->getCatalog($catalogId);
        $model     = $pcClient->getModel($catalogId, $modelId);
        $filterSet = $this->getFilterSet($catalogId, $modelId, $filterSetStateStr);
        $cars      = $pcClient->getCars($catalogId, $modelId, $filterSet->getState()->getIdxList());

        if (!$catalog || !$model || !$cars) {
            return $this->errorNotFound($request, $response);
        }

        $view = new CarsView($catalog, $model, $cars, $filterSet);
        $view->setTitle($catalog->name . " Cars · Parts Catalogs");
        // Render cars view
        return $this->render($response, 'cars.phtml', $view);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     * @noinspection PhpUnused
     */
    public function vinAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $pcClient = $this->getClient();
        $vin      = Router::getVin($request, $args);
        $view     = new VinView();

        if ($vin && Vin::isValid($vin)) {
            $view->setCars($pcClient->carInfo($vin));
            $view->setVin(new Vin($vin));
        }

        if ($vin && !Vin::isValid($vin)) {
            $view->setError("Vin number not valid");
        }

        return $this->render($response, 'vin.phtml', $view);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     * @noinspection PhpUnused
     */
    public function filtersAction(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $pcClient          = $this->getClient();
        $catalogId         = Router::getCatalogId($request, $args);
        $modelId           = Router::getModelId($request, $args);
        $filterSetStateStr = Router::getFilterState($request, $args);
        $filterSet         = $this->getFilterSet($catalogId, $modelId, $filterSetStateStr);

        $view            = new FiltersView;
        $view->catalog   = $pcClient->getCatalog($catalogId);
        $view->model     = $pcClient->getModel($catalogId, $modelId);
        $view->filterSet = $filterSet;

        return $this->render($response, 'filters.phtml', $view);
    }

    /**
     * @param string         $catalogId
     * @param string         $modelId
     * @param FilterSetState $filterSetState
     *
     * @return FilterSet
     * @throws ClientException
     */
    private function getFilterSet($catalogId, $modelId, string $filterSetStateStr)
    {
        $pcClient       = $this->getClient();
        $filterSetState = FilterSetState::fromString($filterSetStateStr);
        $idxList        = $filterSetState->getIdxList();

        $cpAll       = $pcClient->getCarsParameters($catalogId, $modelId);
        $cpAvailable = $pcClient->getCarsParameters($catalogId, $modelId, $idxList);

        return new FilterSet($cpAll, $cpAvailable, $filterSetState);
    }
}
