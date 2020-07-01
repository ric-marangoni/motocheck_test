<?php

declare(strict_types=1);

namespace App\Application\Handler;


use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ForbiddenHandler implements RequestHandlerInterface
{

    public const TEMPLATE_DEFAULT = 'error::403';
    public const LAYOUT_DEFAULT = 'layout::default';

    /**
     * @var TemplateRendererInterface|null
     */
    private $renderer;

    /**
     * @var callable
     */
    private $responseFactory;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $layout;

    public function __construct(
        callable $responseFactory,
        TemplateRendererInterface $renderer = null,
        string $template = self::TEMPLATE_DEFAULT,
        string $layout = self::LAYOUT_DEFAULT
    ) {
        // Factory cast to closure in order to provide return type safety.
        $this->responseFactory = function () use ($responseFactory) : ResponseInterface {
            return $responseFactory();
        };
        $this->renderer = $renderer;
        $this->template = $template;
        $this->layout = $layout;
    }

    /**
     * Creates and returns a 403 response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->renderer === null) {
            return $this->generatePlainTextResponse($request);
        }

        return $this->generateTemplatedResponse($this->renderer, $request);
    }

    /**
     * Generates a plain text response indicating the request method and URI.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function generatePlainTextResponse(ServerRequestInterface $request) : ResponseInterface
    {
        $response = ($this->responseFactory)()->withStatus(StatusCodeInterface::STATUS_FORBIDDEN);
        $response->getBody()
            ->write(sprintf(
                'Cannot %s %s',
                $request->getMethod(),
                (string) $request->getUri()
            ));

        return $response;
    }

    /**
     * Generates a response using a template.
     *
     * Template will receive the current request via the "request" variable.
     *
     * @param TemplateRendererInterface $renderer
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function generateTemplatedResponse(
        TemplateRendererInterface $renderer,
        ServerRequestInterface $request
    ) : ResponseInterface {

        $response = ($this->responseFactory)()->withStatus(StatusCodeInterface::STATUS_FORBIDDEN);
        $response->getBody()->write(
            $renderer->render($this->template, ['request' => $request, 'layout' => $this->layout])
        );

        return $response;
    }
}
