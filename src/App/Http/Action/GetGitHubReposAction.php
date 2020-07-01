<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Application\Service\GitHubService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetGitHubReposAction implements RequestHandlerInterface
{
    /** @var GitHubService */
    private $service;

    public function __construct(
        GitHubService $service
    ) {
        $this->service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse($this->service->import());
    }
}
