<?php

ini_set('display_errors', 1);
error_reporting(-1);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/config_dev.php';
require __DIR__.'/../app/bootstrap.php';

$app->mount('/', new Controllers\FrontController());
$app->mount('/admin', new Controllers\AdminController());

$app->error(function (\Exception $e) use ($app) {
        if ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return $app['twig']->render('404.html.twig', array('code' => 404));
        }
        else{
        	return new Symfony\Component\HttpFoundation\Response('We are sorry, but something went terribly wrong.');
        }
 
        /*$code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
        return $app['twig']->render('error.twig', array('code' => $code));*/
    }
);

$app->run();