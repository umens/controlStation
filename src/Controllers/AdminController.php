<?php

namespace Controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;

class AdminController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {

            return "Brought to you by admin !";
            		
			return $app['twig']->render('dashboard.html.twig', array('title' => 'Administration', 'name' => $userInfo['name']));
        
        })->bind('dashboard');

        return $controllers;
    }
}
