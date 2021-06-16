<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\ClientException;
use PartsCatalogsClient\GroupPath;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\PartsView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PartsController extends AbstractController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function partsAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $view = $this->partsAndSchemaView($request, $args);

        // Render index view
        return $this->render($response, 'parts.phtml', $view);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function schemaAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $view = $this->partsAndSchemaView($request, $args);

        // Render index view
        return $this->render($response, 'schema.phtml', $view);
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $args
     *
     * @return PartsView
     * @throws ClientException
     */
    public function partsAndSchemaView(ServerRequestInterface $request, array $args): PartsView
    {
        $pcClient  = $this->getClient();
        $catalogId = Router::getCatalogId($request, $args);
        $carId     = Router::getCarId($request, $args);
        $criteria  = Router::getCriteria($request, $args);
        $groupPath = GroupPath::fromString(Router::getGroupPath($request, $args));

        $carView = $this->getCarView($catalogId, $carId, $criteria);
        $catalog = $pcClient->getCatalog($catalogId);
        $group   = $pcClient->getGroupByPath($catalogId, $carId, $groupPath);
        $scheme  = $pcClient->getSchemeAndPartsByGroupPath($catalogId, $carId, $groupPath, $criteria);
        $title   = $catalog->name . ' Parts · Parts Catalogs';

        $view = new PartsView($catalog, $carView, $group, $scheme);
        $view->setTitle($title);
        return $view;
    }
}
