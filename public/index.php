<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use Twig\Environment;
use App\Routing\Router;
use App\Config\Connection;
use App\Config\TwigEnvironment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;
use App\DependecyInjection\Container;
use App\Routing\RouteNotFoundException;
use App\Session\Session;

// Env vars - Possibilité d'utiliser le pattern Adapter
// Pour pouvoir varier les dépendances qu'on utilise
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// BDD
$connection = new Connection();
$entityManager = $connection->init();

// Twig - Vue
$twigEnvironment = new TwigEnvironment();
$twig = $twigEnvironment->init();

// Session
$session = new Session();

//Container

$container = new Container();
$container->set(EntityManager::class, $entityManager);
$container->set(Environment::class, $twig);
$container->set(Session::class, $session);
// Routage
$router = new Router($container);
$router->registerRoutes();

if (php_sapi_name() == 'cli'){
  return;
}


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
  $router->execute($requestUri, $requestMethod);
} catch (RouteNotFoundException $e) {
  http_response_code(404);
  echo $twig->render('404.html.twig', ['title' => $e->getMessage()]);
}
