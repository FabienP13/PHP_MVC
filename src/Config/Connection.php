<?php

namespace App\Config;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Connection
{
  public function init(): EntityManager
  {
    // On indique à Doctrine où aller chercher les entités
    // pour les analyser et les mapper dans la base de données
    $paths = [__DIR__ . "/../Entity"];
    $isDevMode = ($_ENV['APP_ENV'] === 'dev');

    $dbParams = [
      'driver'   => $_ENV['DB_DRIVER'],
      'host'     => $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT'],
      'user'     => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASSWORD'],
      'dbname'   => $_ENV['DB_NAME'],
    ];

    $config = Setup::createAnnotationMetadataConfiguration(
      $paths,
      $isDevMode,
      null,
      null,
      false
    );

    // Un gestionnaire d'entités = paramètres de connexion + objet configuration
    return EntityManager::create($dbParams, $config);
  }
}
