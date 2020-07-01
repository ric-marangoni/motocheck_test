<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Application;

use App\Application\Service;
use App\Http\Action;
use App\Infrastructure\Container\Application\Factory;
use App\Infrastructure\Rest\Repository\ApiGitHubRepository;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'twig'         => $this->getTwig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [],
            'factories' => [
                // Actions - General
                Action\HomeAction::class => new Factory\Action\TemplateActionFactory(Service\GitHubService::class),
                Action\GetGitHubReposAction::class => new Factory\Action\TemplateActionFactory(Service\GitHubService::class),
                Action\GetRepositoriesAction::class => new Factory\Action\TemplateActionFactory(Service\GitHubService::class),
                Action\GetSellerReportAction::class => new Factory\Action\TemplateActionFactory(Service\GitHubService::class),

                Service\GitHubService::class => Factory\GitHubServiceFactory::class,

                //Repositories
                ApiGitHubRepository::class => Factory\ApiGitHubRepositoryFactory::class,

                //Infrastructure
                Service\CacheService::class => Factory\CacheServiceFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'    => ['src/App/Application/Templates/app'],
                'error'  => ['src/App/Application/Templates/error'],
                'layout' => ['src/App/Application/Templates/layout'],
            ],
        ];
    }

    public function getTwig()
    {
        return [
            //'cache_dir' => 'data/cache/twig',
            'assets_url' => '/',
            'assets_version' => null,
            'extensions' => [

            ],
            'runtime_loaders' => [
                // runtime loader names or instances
            ],
            'globals' => [
                // Variables to pass to all twig templates
            ],
            // 'timezone' => 'default timezone identifier; e.g. America/Chicago',
        ];
    }
}
