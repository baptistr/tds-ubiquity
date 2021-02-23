<?php
use Ubiquity\controllers\Router;

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();
//Router::addRoute("_default", "controllers\\MainController");
//\Ubiquity\assets\AssetsManager::start($config);
\Ubiquity\assets\AssetsManager::start($config);
