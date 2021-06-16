<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\ClientException;
use PartsCatalogsClient\GroupPath;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\GroupsView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GroupsController extends AbstractController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function groupsAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $pcClient    = $this->getClient();
        $queryParams = $request->getQueryParams();
        $catalogId   = $args['catalogId'];
        $carId       = Router::getCarId($request, $args);
        $criteria    = Router::getCriteria($request, $args);
        $groupPath   = GroupPath::fromString(isset($queryParams['gp']) ? $queryParams['gp'] : '');

        $catalog      = $pcClient->getCatalog($catalogId);
        $carView      = $this->getCarView($catalogId, $carId, $criteria);
        $groups       = $pcClient->getGroupsByPath($catalogId, $carId, $groupPath);
        $currentGroup = $pcClient->getGroupByPath($catalogId, $carId, $groupPath);
        $title        = $catalog->name . ' Groups · Parts Catalogs';

        $view = new GroupsView($catalog, $carView, $currentGroup, $groups);
        $view->setTitle($title);

        // Render index view
        return $this->render($response, 'groups.phtml', $view);
    }
}
