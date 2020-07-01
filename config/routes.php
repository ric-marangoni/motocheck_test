<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * @param Application $app
 * @param MiddlewareFactory $factory
 * @param ContainerInterface $container
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 *
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->route('/', [
        \App\Http\Action\HomeAction::class
    ], ['GET'], 'home');

    $app->route('/api/v1/get-github-repos', [
        \App\Http\Action\GetGitHubReposAction::class
    ], ['GET'], 'githubrepos.get');

    $app->route('/reports/get-repositories', [
        \App\Http\Action\GetRepositoriesAction::class
    ], ['GET'], 'reports.get.repositories');

    $app->route('/reports/get-seller-report', [
        \App\Http\Action\GetSellerReportAction::class
    ], ['GET'], 'reports.get.seller.report');
};
