<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    'dependencies' => [
        'delegators' => [
            \Zend\Stratigility\Middleware\ErrorHandler::class => [
                \App\Infrastructure\Container\Application\Factory\BugsnagFactory::class,
            ],
        ],
        'aliases' => [
            Zend\Expressive\Authentication\UserRepositoryInterface::class =>
                Zend\Expressive\Authentication\UserRepository\PdoDatabase::class,
            Zend\Expressive\Authorization\AuthorizationInterface::class =>
                \App\Infrastructure\Container\Application\Utils\Auth\SystemAcl::class,
            \Symfony\Contracts\HttpClient\ResponseInterface::class =>
                \Psr\Http\Message\ResponseInterface::class
        ],
        'invokables' => [
        ],
        'factories'  => [
            'bugsnag' => function (ContainerInterface $container) {
                $key = $container->get("config")['bugsnag']['key'];

                $bugsnag = Bugsnag\Client::make($key);

                $bugsnag->getConfig()->setReleaseStage(getenv('APPLICATION_ENV'));
                $bugsnag->getConfig()->setNotifyReleaseStages($container->get("config")['bugsnag']['notify']);

                return $bugsnag;
            },

            // ACL
            \App\Infrastructure\Container\Application\Utils\Auth\SystemAcl::class =>
                \App\Infrastructure\Container\Application\Utils\Auth\SystemAclFactory::class,

            // Flash Message
            \Zend\Expressive\Flash\FlashMessageMiddleware::class =>
                \App\Infrastructure\Container\Application\Factory\FlashMessageMiddlewareFactory::class,

            // Doctrine
            'doctrine.entity_manager.orm_default' =>
                \App\Infrastructure\Container\Infrastructure\DoctrineEntityManagerFactory::class,

            \Doctrine\ORM\Mapping\UnderscoreNamingStrategy::class =>
                \Zend\ServiceManager\Factory\InvokableFactory::class,

            Zend\Expressive\Authentication\AuthenticationInterface::class =>
                Zend\Expressive\Authentication\Session\PhpSessionFactory::class,

            //Plugins
            \Overtrue\Socialite\SocialiteManager::class =>
                \App\Infrastructure\Container\Application\Factory\Social\SocialiteFactory::class,

            //Handlers
            \App\Application\Handler\ForbiddenHandler::class =>
                \App\Infrastructure\Container\Application\Factory\Handler\ForbiddenHandlerFactory::class,



            //Middlewares
            \App\Application\Middleware\AuthorizationMiddleware::class =>
                \App\Infrastructure\Container\Application\Factory\AuthorizationMiddlewareFactory::class,
            \App\Application\Middleware\TwigMiddleware::class =>
                \App\Infrastructure\Container\Application\Factory\TwigMiddlewareFactory::class,
            \App\Application\Middleware\HttpHandleMiddleware::class =>
                \App\Infrastructure\Container\Application\Factory\HttpHandleMiddlewareFactory::class,
        ],
    ],
];
