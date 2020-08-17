<?php
require_once __DIR__ . "/bootstrap.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($container->get(\Doctrine\ORM\EntityManager::class));
