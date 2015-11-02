<?php

    use Silex\Application,
        Silex\Provider\TwigServiceProvider,
        Wunderbaum\Controllers;

    defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(__DIR__));
    require_once APPLICATION_ROOT.'/vendor/autoload.php';

    // Set up some application defaults
    $app = new Application();
    $app['debug'] = defined('DEBUG_MODE') && DEBUG_MODE;
    $app['images_path'] = '/images/';
    $app['css_path'] = '/css/';

    // Set application resources
    $app['resources'] = [
        'varnish_log' => APPLICATION_ROOT.'/resources/varnish.log',
        'news_rss'    => APPLICATION_ROOT.'/resources/news.rss',
        'news_json'   => APPLICATION_ROOT.'/resources/news.json'
    ];

    // Register url generator and twig services for templates
    $app->register(new Silex\Provider\UrlGeneratorServiceProvider());
    $app->register(new TwigServiceProvider(), array(
        'twig.path' => APPLICATION_ROOT.'/views',
    ));

    // Mount main routes on top level
    $app->mount('', new Controllers\Main());

    // Return the application so we can re-use it in initialization scripts and phpunit
    return $app;
