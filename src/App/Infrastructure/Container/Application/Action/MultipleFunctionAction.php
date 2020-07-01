<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Application\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Flash\FlashMessages;

abstract class MultipleFunctionAction
{
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var RequestHandlerInterface $handler */
    protected $handler;

    /** @var FlashMessages $flashMessages */
    protected $flashMessages;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $actionFunction = $request->getAttribute('function', '');
        $this->request = $request;
        $this->handler  = $handler;
        $this->flashMessages = $this->request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);

        $function = sprintf(
            '%s%s',
            strtolower($request->getMethod()),
            ucfirst($actionFunction)
        );

        return $this->$function();
    }
}