<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
// use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;


require '../vendor/autoload.php';

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('BASE_URL', 'http://localhost:8080/');


// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);


$loader->register();

$container = new FactoryDefault();

$loader->registerNamespaces(
    [
        'App\Listeners' => APP_PATH . '/listeners'
    ]
);


$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);



$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'events',
            ]
        );
    }
);



$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client('mongodb://mongo', array('username' => 'root', "password" => 'password123'));
        return $mongo->ACL;
    }
);

// Event Managements ---------------------------------- START ------------------------------------

$eventsManager = new EventsManager();

$eventsManager->attach(
    'defaults',
    new \App\Listeners\defaultProviders()
    // function (Event $event, $connection) {
    //     // something to perform...
    // }
);

$eventsManager->attach(
    'application:beforeHandleRequest',
    new App\Listeners\notificationsListeners()
);

$container->set(
    'eventsManager',
    $eventsManager
);
$application->setEventsManager($eventsManager);
// $application = new Application($container);


// Event Managements ---------------------------------- STOP ------------------------------------


try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
