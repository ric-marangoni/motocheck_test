<?php

declare(strict_types=1);
namespace App\Infrastructure\Container\Application\Factory\Handler;

use App\Application\Handler\ForbiddenHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ForbiddenHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForbiddenHandler
    {
        $config   = $container->has('config') ? $container->get('config') : [];
        $renderer = $container->has(TemplateRendererInterface::class)
            ? $container->get(TemplateRendererInterface::class)
            : null;
        $template = $config['zend-expressive']['error_handler']['template_403']
            ?? ForbiddenHandler::TEMPLATE_DEFAULT;
        $layout   = $config['zend-expressive']['error_handler']['layout']
            ?? ForbiddenHandler::LAYOUT_DEFAULT;

        return new ForbiddenHandler(
            $container->get(ResponseInterface::class),
            $renderer,
            $template,
            $layout
        );
    }
}
