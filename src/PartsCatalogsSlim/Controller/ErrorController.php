<?php

namespace PartsCatalogsSlim\Controller;

use Closure;
use PartsCatalogsClient\ClientException;
use PartsCatalogsSlim\View\ErrorView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class ErrorController extends AbstractController
{
    /**
     * @var bool
     */
    private $displayErrorDetails;

    /**
     * @param PhpRenderer $render
     *
     * @return Closure
     */
    public static function errorHandler(PhpRenderer $render, bool $displayErrorDetails): Closure
    {
        $controller = new ErrorController($render);
        $controller->setDisplayErrorDetails($displayErrorDetails);

        return function ($request, $response, $exception) use ($controller) {
            return $controller->errorAction($request, $response, $exception);
        };
    }

    public function errorAction(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $code    = 500;
        $message = "Internal Server Error";
        $details = '';

        if ($args instanceof ClientException) {
            $code    = 500;
            $details = "Catalog temporarily unavailable";
            if ($this->displayErrorDetails) {
                $details = $args->getMessage();
            }
        }
        if ($args instanceof ClientException and $args->getCode() == 404) {
            return $this->notFoundAction($request, $response, []);
        }

        $response = $response->withStatus($code);
        $view     = new ErrorView($code, $message, $details);
        $view->setTitle($message);

        return $this->render($response, 'error.phtml', $view);
    }

    public static function notfoundHandler(PhpRenderer $render)
    {
        return function ($request, $response) use ($render) {
            $controller = new ErrorController($render);
            return $controller->notFoundAction($request, $response, []);
        };
    }

    public function setDisplayErrorDetails(bool $displayErrorDetails)
    {
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function notFoundAction(ServerRequestInterface $request, ResponseInterface $response, array $array)
    {
        $code    = 404;
        $message = 'Not Found';
        $details = '';

        $response = $response->withStatus($code);
        $view     = new ErrorView($code, $message, $details);
        $view->setTitle($message);

        return $this->render($response, 'error.phtml', $view);
    }
}
