<?php declare(strict_types=1);

use DI\Container;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr16CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Kodus\Cache\FileCache;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use PartsCatalogsClient\Client as PcClient;
use PartsCatalogsSlim\Controller\CarsController;
use PartsCatalogsSlim\Controller\CatalogsController;
use PartsCatalogsSlim\Controller\ErrorController;
use PartsCatalogsSlim\Controller\GroupsController;
use PartsCatalogsSlim\Controller\ModelsController;
use PartsCatalogsSlim\Controller\PartsController;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Slim\App;
use Slim\Handlers\Error;
use Slim\Views\PhpRenderer;


$container = new Container();

// view renderer
$container->set('renderer', function (ContainerInterface $c) {
    $settings         = $c->get('settings');
    $priceUrlTemplate = $settings['searchUrlTemplate'];
    $render           = new PhpRenderer($settings['renderer']['template_path'], [
        //            'searchUrlTemplate' => $searchUrlTemplate
        ]);
    $render->addAttribute("searchUrlTemplate", $priceUrlTemplate);
    $render->setLayout("layout.phtml");
    return $render;
});

// monolog
$container->set('logger', function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger   = new Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
    return $logger;
});

$container->set('httpClient', function (ContainerInterface $c) {
    /** @var CacheInterface $cache */
    $cache = $c->get('psrCache');
    $ttl   = $c->get('settings')['cache']['ttl'];

    $psrCache      = new Psr16CacheStorage($cache);
    $cacheStrategy = new GreedyCacheStrategy($psrCache, $ttl); // 24h

    // Create default HandlerStack
    $stack = HandlerStack::create();
    // Add this middleware to the top with `push`
    $stack->push(new CacheMiddleware($cacheStrategy), 'cache');

    // Initialize the client with the handler option
    return new HttpClient(['handler' => $stack]);
});

$container->set('pcClient', function (ContainerInterface $c) {
    $apiKey     = $c->get('settings')['partsCatalogs']['apiKey'];
    $httpClient = $c->get('httpClient');

    return new PcClient($apiKey, [], $httpClient);
});

$container->set('psrCache', function (ContainerInterface $c) {
    $ttl = $c->get('settings')['cache']['ttl'];

    return new FileCache(__DIR__ . '/../cache', $ttl);
});

$container->set('template', function () {

});

$container->set(CarsController::class, function (ContainerInterface $c) {
    return new CarsController($c->get('renderer'), $c->get('pcClient'));
});

$container->set(CatalogsController::class, function (ContainerInterface $c) {
    return new CatalogsController($c->get('renderer'), $c->get('pcClient'));
});

$container->set(GroupsController::class, function (ContainerInterface $c) {
    return new GroupsController($c->get('renderer'), $c->get('pcClient'));
});

$container->set(ModelsController::class, function (ContainerInterface $c) {
    return new ModelsController($c->get('renderer'), $c->get('pcClient'));
});

$container->set(PartsController::class, function (ContainerInterface $c) {
    return new PartsController($c->get('renderer'), $c->get('pcClient'));
});

$container->set('errorHandler', function (ContainerInterface $c) {
    return ErrorController::errorHandler($c->get('renderer'), $c->get('settings')['displayErrorDetails']);
});

$container->set('notFoundHandler', function (ContainerInterface $c) {
    return ErrorController::notfoundHandler($c->get('renderer'));
});

return $container;