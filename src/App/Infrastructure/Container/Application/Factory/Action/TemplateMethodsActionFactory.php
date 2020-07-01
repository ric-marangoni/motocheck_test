<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Application\Factory\Action;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use function get_class;

class TemplateMethodsActionFactory
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function __invoke(ContainerInterface $container, $requestedService) : MiddlewareInterface
    {
        $template = $container->has(TemplateRendererInterface::class)
            ? $container->get(TemplateRendererInterface::class)
            : null;

        $service = $container->get($this->service);

        return new $requestedService($service, $template);
    }
}
