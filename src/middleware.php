<?php declare(strict_types=1);

use Slim\App;
use Slim\HttpCache\Cache;

/**
 * @param App $app
 */
return function (App $app) {
    $app->add(new Cache('public', 86400));
};
