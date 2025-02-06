<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(\dirname(__DIR__).'/.env', \dirname(__DIR__).'/.env.local');

$containerBuilder = new ContainerBuilder();
$loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__));

$containerBuilder->setParameter('image_directory', \dirname(__DIR__).'/images');
$loader->load('../config/services.xml');
$containerBuilder->compile(true);

$application = new Application();
$application->add($containerBuilder->get(\Anyvoid\W3bNasaPinata\App\Commands\GenerateEarthCommand::class));

$application->run();
