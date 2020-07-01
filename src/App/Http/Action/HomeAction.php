<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Application\Service\GitHubService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomeAction implements RequestHandlerInterface
{
    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var GitHubService */
    private $service;

    public function __construct(
        GitHubService $service,
        ?TemplateRendererInterface $template = null
    ) {
        $this->template      = $template;
        $this->service       = $service;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse($this->template->render('app::reports/index'));
    }
}
