<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\ClientException;
use PartsCatalogsSlim\View\CatalogsView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CatalogsController extends AbstractController
{
    /**
     * Show list of available catalogs
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     * @throws ClientException
     */
    public function catalogsAction(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pcClient = $this->getClient();
        $catalogs = $pcClient->getCatalogs();
        $title    = 'Catalogs · Parts Catalogs';

        $view = new CatalogsView($catalogs);
        $view->setTitle($title);

        // Render index view
        return $this->render($response, 'catalogs.phtml', $view);
    }
}
