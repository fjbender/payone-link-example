<?php

// Load dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// DI Container
$builder = new \DI\ContainerBuilder();
$builder->useAutowiring(true);
$builder->addDefinitions([
        // Tiny \Twig\Environment factory
        \Twig\Environment::class => function (\Psr\Container\ContainerInterface $c) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/View/');
            return new \Twig\Environment($loader);
        }
    ]
);
$container = $builder->build();