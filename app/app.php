<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
// TwigServiceProvider para utilizar twig templates
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
// Services for Form and translation 
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new silex\DAO\UserDAO($app['db']);
            }),
        ),
    ),
));

// Register services.
$app['dao.article'] = $app->share(function ($app) {
    return new silex\DAO\ArticleDAO($app['db']);
});

$app['dao.user'] = $app->share(function ($app) {
    return new silex\DAO\UserDAO($app['db']);
});

$app['dao.comment'] = $app->share(function ($app) {
    $commentDAO = new silex\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDao($app['dao.user']);
    return $commentDAO;
});