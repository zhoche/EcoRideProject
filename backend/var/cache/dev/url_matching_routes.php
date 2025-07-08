<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_wdt/styles' => [[['_route' => '_wdt_stylesheet', '_controller' => 'web_profiler.controller.profiler::toolbarStylesheetAction'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/admin/rides-per-day' => [[['_route' => 'admin_rides_per_day', '_controller' => 'App\\Controller\\AdminController::getRidesPerDay'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/credits-earned-per-day' => [[['_route' => 'admin_credits_per_day', '_controller' => 'App\\Controller\\AdminController::getCreditsPerDay'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/credits-earned-total' => [[['_route' => 'admin_credits_total', '_controller' => 'App\\Controller\\AdminController::getTotalCreditsEarned'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/employee-list' => [[['_route' => 'api_employees_list', '_controller' => 'App\\Controller\\AdminController::getEmployees'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/suspended-user' => [[['_route' => 'admin_suspend_user', '_controller' => 'App\\Controller\\AdminController::suspendUser'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/suspended-users-list' => [[['_route' => 'admin_suspended_users', '_controller' => 'App\\Controller\\AdminController::getSuspendedUsers'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/suspended-emails' => [[['_route' => 'admin_suspended_emails', '_controller' => 'App\\Controller\\AdminController::getSuspendedEmails'], null, ['GET' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\ApiRegisterController::register'], null, ['POST' => 0, 'OPTIONS' => 1], null, false, false, null]],
        '/api/employe/feedback/authorization' => [[['_route' => 'app_employe_authorizefeedback', '_controller' => 'App\\Controller\\EmployeController::authorizeFeedback'], null, ['PATCH' => 0], null, false, false, null]],
        '/api/employe/avis/a-traiter' => [[['_route' => 'app_employe_getpendingavis', '_controller' => 'App\\Controller\\EmployeController::getPendingAvis'], null, ['GET' => 0], null, false, false, null]],
        '/api/employe/avis/historique' => [[['_route' => 'app_employe_getarchivedavis', '_controller' => 'App\\Controller\\EmployeController::getArchivedAvis'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/test-user' => [[['_route' => 'app_ride_testuser', '_controller' => 'App\\Controller\\RideController::testUser'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/new-ride' => [[['_route' => 'app_ride_createride', '_controller' => 'App\\Controller\\RideController::createRide'], null, ['POST' => 0], null, false, false, null]],
        '/api/rides/list' => [[['_route' => 'app_ride_getalluserrides', '_controller' => 'App\\Controller\\RideController::getAllUserRides'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/feedback' => [[['_route' => 'app_ride_givefeedback', '_controller' => 'App\\Controller\\RideController::giveFeedback'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [
            [['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null],
            [['_route' => 'api_login'], null, ['POST' => 0], null, false, false, null],
        ],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/api/redirect-role' => [[['_route' => 'redirect_role', '_controller' => 'App\\Controller\\UserController::redirectRole'], null, ['GET' => 0], null, false, false, null]],
        '/api/vehicles/user' => [[['_route' => 'get_user_vehicles', '_controller' => 'App\\Controller\\VehicleController::getUserVehicles'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/api/(?'
                    .'|admin/employee\\-delete/([^/]++)(*:241)'
                    .'|rides/([^/]++)/(?'
                        .'|register(*:275)'
                        .'|u(?'
                            .'|nregister(*:296)'
                            .'|pdate(*:309)'
                        .')'
                        .'|delete(*:324)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        241 => [[['_route' => 'admin_employee_delete', '_controller' => 'App\\Controller\\AdminController::deleteEmployee'], ['id'], ['DELETE' => 0], null, false, true, null]],
        275 => [[['_route' => 'app_ride_registertoride', '_controller' => 'App\\Controller\\RideController::registerToRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        296 => [[['_route' => 'app_ride_unregisterfromride', '_controller' => 'App\\Controller\\RideController::unregisterFromRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        309 => [[['_route' => 'app_ride_updateride', '_controller' => 'App\\Controller\\RideController::updateRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        324 => [
            [['_route' => 'app_ride_deleteride', '_controller' => 'App\\Controller\\RideController::deleteRide'], ['ride_id'], ['POST' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
