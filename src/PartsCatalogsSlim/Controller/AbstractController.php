<?php declare(strict_types=1);

namespace PartsCatalogsSlim\Controller;

use PartsCatalogsClient\Client;
use PartsCatalogsClient\ClientException;
use PartsCatalogsSlim\View\CarsView\CarView;
use PartsCatalogsSlim\View\LayoutView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;
use Slim\Views\PhpRenderer;

abstract class AbstractController
{
    private $render;
    private $pcClient;

    public function __construct(PhpRenderer $render, Client $pcClient = null)
    {
        $this->render   = $render;
        $this->pcClient = $pcClient;
    }

    public function getClient(): Client
    {
        return $this->pcClient;
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $criteria
     *
     * @return CarView|null
     * @throws ClientException
     */
    protected function getCarView(string $catalogId, string $carId, string $criteria): ?CarView
    {
        $car = $this->pcClient->getCar($catalogId, $carId, $criteria);
        if ($car) {
            return CarView::fromCar($car);
        }
        return null;
    }

    public function render(ResponseInterface $response, string $string, LayoutView $view): ResponseInterface
    {
        return $this->render->render($response, $string, ['view' => $view]);
    }

    public function errorNotFound(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        throw new NotFoundException($request, $response);
    }
}
