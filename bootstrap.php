<?php

// Load dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// Set up doctrine
$isDevMode = $_ENV['APPLICATION_MODE'] ?? false;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/data/db.sqlite',
);

// DI Container
$builder = new \DI\ContainerBuilder();
$builder->useAutowiring(true);
$builder->addDefinitions([
        // Tiny \Twig\Environment factory
        \Twig\Environment::class => function (\Psr\Container\ContainerInterface $c) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/View/');
            return new \Twig\Environment($loader);
        },
        // Tiny EntityManager factory
        \Doctrine\ORM\EntityManager::class => function (\Psr\Container\ContainerInterface $c) use ($conn, $config) {
            return $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
        }
    ]
);
$container = $builder->build();