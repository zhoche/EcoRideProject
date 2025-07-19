<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/admin/rides-per-day' => [[['_route' => 'admin_rides_per_day', '_controller' => 'App\\Controller\\AdminController::getRidesPerDay'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/credits-earned-per-day' => [[['_route' => 'admin_credits_per_day', '_controller' => 'App\\Controller\\AdminController::getCreditsPerDay'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/credits-earned-total' => [[['_route' => 'admin_credits_total', '_controller' => 'App\\Controller\\AdminController::getTotalCreditsEarned'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/employee-list' => [[['_route' => 'api_employees_list', '_controller' => 'App\\Controller\\AdminController::getEmployees'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/suspended-user' => [[['_route' => 'admin_suspend_user', '_controller' => 'App\\Controller\\AdminController::suspendUser'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/suspended-users-list' => [[['_route' => 'admin_suspended_users', '_controller' => 'App\\Controller\\AdminController::getSuspendedUsers'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/suspended-emails' => [[['_route' => 'admin_suspended_emails', '_controller' => 'App\\Controller\\AdminController::getSuspendedEmails'], null, ['GET' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\ApiRegisterController::register'], null, ['POST' => 0, 'OPTIONS' => 1], null, false, false, null]],
        '/' => [[['_route' => 'homepage', '_controller' => 'App\\Controller\\DefaultController::index'], null, null, null, false, false, null]],
        '/api/employe/feedback/authorization' => [[['_route' => 'app_employe_authorizefeedback', '_controller' => 'App\\Controller\\EmployeController::authorizeFeedback'], null, ['PATCH' => 0], null, false, false, null]],
        '/api/employe/avis/a-traiter' => [[['_route' => 'app_employe_getpendingavis', '_controller' => 'App\\Controller\\EmployeController::getPendingAvis'], null, ['GET' => 0], null, false, false, null]],
        '/api/employe/avis/historique' => [[['_route' => 'app_employe_getarchivedavis', '_controller' => 'App\\Controller\\EmployeController::getArchivedAvis'], null, ['GET' => 0], null, false, false, null]],
        '/healthz' => [[['_route' => 'healthz', '_controller' => 'App\\Controller\\HealthController::health'], null, null, null, false, false, null]],
        '/run-migrations' => [[['_route' => 'run_migrations', '_controller' => 'App\\Controller\\MigrationController::run'], null, null, null, false, false, null]],
        '/api/rides/test-user' => [[['_route' => 'app_ride_testuser', '_controller' => 'App\\Controller\\RideController::testUser'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/new-ride' => [[['_route' => 'app_ride_createride', '_controller' => 'App\\Controller\\RideController::createRide'], null, ['POST' => 0], null, false, false, null]],
        '/api/rides/list' => [[['_route' => 'app_ride_getalluserrides', '_controller' => 'App\\Controller\\RideController::getAllUserRides'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/feedback' => [[['_route' => 'app_ride_givefeedback', '_controller' => 'App\\Controller\\RideController::giveFeedback'], null, ['POST' => 0], null, false, false, null]],
        '/api/rides/search' => [[['_route' => 'app_ride_search', '_controller' => 'App\\Controller\\RideController::searchRides'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/next-available' => [[['_route' => 'app_ride_next', '_controller' => 'App\\Controller\\RideController::nextAvailable'], null, ['GET' => 0], null, false, false, null]],
        '/api/rides/feedback/check' => [[['_route' => 'app_ride_checkfeedback', '_controller' => 'App\\Controller\\RideController::checkFeedback'], null, ['GET' => 0], null, false, false, null]],
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
                .'|/api/(?'
                    .'|admin/employee\\-delete/([^/]++)(*:46)'
                    .'|rides/([^/]++)/(?'
                        .'|register(*:79)'
                        .'|u(?'
                            .'|nregister(*:99)'
                            .'|pdate(*:111)'
                        .')'
                        .'|delete(*:126)'
                        .'|terminate(*:143)'
                    .')'
                .')'
                .'|/users/([^/]++)/average\\-rating(*:184)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        46 => [[['_route' => 'admin_employee_delete', '_controller' => 'App\\Controller\\AdminController::deleteEmployee'], ['id'], ['DELETE' => 0], null, false, true, null]],
        79 => [[['_route' => 'app_ride_registertoride', '_controller' => 'App\\Controller\\RideController::registerToRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        99 => [[['_route' => 'app_ride_unregisterfromride', '_controller' => 'App\\Controller\\RideController::unregisterFromRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        111 => [[['_route' => 'app_ride_updateride', '_controller' => 'App\\Controller\\RideController::updateRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        126 => [[['_route' => 'app_ride_deleteride', '_controller' => 'App\\Controller\\RideController::deleteRide'], ['ride_id'], ['POST' => 0], null, false, false, null]],
        143 => [[['_route' => 'app_ride_terminate', '_controller' => 'App\\Controller\\RideController::terminateRide'], ['id'], ['POST' => 0], null, false, false, null]],
        184 => [
            [['_route' => 'app_user_getdriveraveragerating', '_controller' => 'App\\Controller\\UserController::getDriverAverageRating'], ['id'], ['GET' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
