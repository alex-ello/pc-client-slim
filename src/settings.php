<?php declare(strict_types=1);

use Monolog\Logger;

return [
    'debug' => false,
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header

    // Renderer settings
    'renderer' => [
        'template_path' => __DIR__ . '/../templates/',
    ],

    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
        'level' => Logger::DEBUG,
    ],

    // Parts-Catalogs settings
    'partsCatalogs' => [
        'apiKey' => '',
    ],

    /**
     * Possible variables
     * code - part article
     * brand - part brand
     **/
    'searchUrlTemplate' => '/?code={code}&brand={brand}',

    // Cache settings
    'cache' => [
        'ttl' => 60 * 60 * 24
    ],
];
