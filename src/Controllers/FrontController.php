<?php

namespace Controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {

            $template_name = 'index.html.twig';
            $cache_headers = $app['cache.defaults'];
            // Updates the Last-Modified HTTP header
            $cache_headers['Last-Modified'] = date('r', $app['twig.template_loader']($template_name));

            $token = $app['security']->getToken();
            if (null !== $token) {
                $user = $token->getUser();
            }



            // Builds the response
            $response = $app['twig']->render($template_name, array(
                'user' => $user,
            ));

            // Sends the response
            return new Response($response, 200, $app['debug'] ? array() : $cache_headers);

        })->bind('home');

        $controllers->get('/monitoring', function () use ($app) {

            $token = $app['security']->getToken();
            if (null !== $token) {
                $user = $token->getUser();
            }

            return $app['twig']->render('monitoring.html.twig', array(
                'user' => $user,
            ));

        })->bind('monitoring');

        $controllers->get('/sites', function () use ($app) {

            $token = $app['security']->getToken();
            if (null !== $token) {
                $user = $token->getUser();
            }

            $sql = "SELECT * FROM sites WHERE active = 1";
            $sites = $app['db']->fetchAll($sql);

            return $app['twig']->render('site.html.twig', array(
                'user' => $user,
                'sites'=> $sites,
            ));

        })->bind('site');




        /*
         *
         * route-action de login, ne pas toucher !
         *
         */

        $controllers->get('/login', function (Request $request) use ($app) {

            $token = $app['security']->getToken();

            return $app['twig']->render('login.html.twig', array(
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));

        })->bind('login');

        $controllers->post('/login_check', function (Application $app) { })->bind('check_path');

        return $controllers;

    }
	
}