<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\ClientException;
use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\ModelsView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ModelsController extends AbstractController
{
    /**
     * Show list of available models
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function modelsAction(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pcClient  = $this->getClient();
        $catalogId = Router::getCatalogId($request, $args);
        $catalog   = $pcClient->getCatalog($catalogId);
        $models    = $pcClient->getModels($catalogId);

        $title     = $catalog->name . ' · Parts Catalogs';

        if (!$catalog) {
            return $this->errorNotFound($request, $response);
        }

        $view = new ModelsView($catalog, $models);
        $view->setTitle($title);

        // Render index view
        return $this->render($response, 'models.phtml', $view);
    }
}
