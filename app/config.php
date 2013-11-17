<?php

date_default_timezone_set("Europe/Paris");

// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];
$app['translator.messages'] = array(
    'fr' => __DIR__.'/locales/fr.yml',
    'en' => __DIR__.'/locales/en.yml',
);

// Cache
$app['cache.path'] = __DIR__ . '/../cache';
$app['cache.max_age'] = 3600 * 24 * 90;
$app['cache.expires'] = 3600 * 24 * 90;
$app['cache.defaults'] = array(
    'Cache-Control'     => sprintf('public, max-age=%d, s-maxage=%d, must-revalidate, proxy-revalidate', $app['cache.max_age'], $app['cache.max_age']),
    'Expires'           => date('r', time() + $app['cache.expires'])
);

$app['twig.template_loader'] = $app->protect(function($template_name) use ($app) {
 
    // Returns immediately the current time when in debug mode
    if ($app['debug']) {
        return time();
    }
 
    // Gets the cache file and its modified time
    $cache = $app['twig']->getCacheFilename($template_name);
    $cache_time = is_file($cache) ? filemtime($cache) : 0;
 
    // If there is a newer version of the template
    if (false === $app['twig']->isTemplateFresh($template_name, $cache_time)) {
 
        // Deletes the cached file
        @unlink($cache);
 
        // Flushes the application HTTP cache for the current request
        $app['http_cache']->getStore()->invalidate($app['request']);
 
        // Returns the cache modified file time (as generated now)
        return time();
    }
 
    // Returns the template modified time
    return $cache_time;
});


// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

// database
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'dbhost' => 'localhost',
    'dbname' => 'controlStation',
    'user' => 'root',
    'password' => '',
    'driverOptions' => array(1002 => 'SET NAMES utf8 COLLATE utf8_general_ci'),
    //'path' => $app['cache.dir'].'/app.db', si sqlite
);


//sécurité de l'application
$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
    ),
    'secured' => array(
        'pattern' => '^.*$',
        'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
        "remember_me" => array('key' => 'MySuperMegaGigaNewSecret', 'lifetime' => '31536000'),
        'logout' => array('logout_path' => '/logout'),
        'users' => $app->share(function() use ($app) {
                // La classe Providers\UserProvider est spécifique à l'application
                return new Providers\UserProvider($app['db']);
        }),
    ),
);

$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER'),
);
            
$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
    array('^.*$', 'ROLE_USER'),
    array('^/$', ''),
);