<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    #$routes->applyMiddleware('csrf');

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/test', ['controller' => 'Test', 'action' => 'test', 'isRest' => true]);
    $routes->connect('/test/login', ['controller' => 'Test', 'action' => 'login', 'isRest' => true]);
    $routes->connect('/test/auth', ['controller' => 'Test', 'action' => 'auth', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/character/inventory', ['controller' => 'Character', 'action' => 'inventory', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/character/overview', ['controller' => 'Character', 'action' => 'overview', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/character/abilities', ['controller' => 'Character', 'action' => 'abilities', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/character/forces', ['controller' => 'Character', 'action' => 'forces', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/character/saveuser', ['controller' => 'Character', 'action' => 'saveuser', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/city/apa', ['controller' => 'City', 'action' => 'apa', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/city/bar', ['controller' => 'City', 'action' => 'bar', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/city/layer', ['controller' => 'City', 'action' => 'layer', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/city/arena', ['controller' => 'City', 'action' => 'arena', 'isRest' => true, 'requireAuthorization' => true]);

    $routes->connect('/preferences/fight', ['controller' => 'Preferences', 'action' => 'fight', 'isRest' => true, 'requireAuthorization' => true]);
    
    $routes->connect('/alliances', ['controller' => 'Alliances', 'action' => 'index', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/alliances/raid', ['controller' => 'Alliances', 'action' => 'raid', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/alliances/view', ['controller' => 'Alliances', 'action' => 'view', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/alliances/leave', ['controller' => 'Alliances', 'action' => 'leave', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/alliances/all', ['controller' => 'Alliances', 'action' => 'all', 'isRest' => true, 'requireAuthorization' => true]);



    $routes->connect('/statistics/get/:id', ['controller' => 'Statistics', 'action' => 'get', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/statistics/ranking', ['controller' => 'Statistics', 'action' => 'ranking', 'isRest' => true, 'requireAuthorization' => true]);
    $routes->connect('/statistics/setstat', ['controller' => 'Statistics', 'action' => 'setstat', 'isRest' => true, 'requireAuthorization' => true]);


    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);


    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
