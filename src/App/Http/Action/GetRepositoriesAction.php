<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Application\Service\GitHubService;
use League\Csv\CharsetConverter;
use League\Csv\Writer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetRepositoriesAction implements RequestHandlerInterface
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
        $params = $request->getQueryParams();

        $exportCsv = false;
        if (isset($params['exportCsv'])) {
            $exportCsv = true;
        }

        $data = $this->service->getRepositories((int)$params['offset'], (int)$params['limit'], $exportCsv);

        if (isset($params['exportCsv'])) {
            $header = [
                'Owner',
                'Project',
                'Stars'
            ];

            $records = [];

            foreach ($data['rows'] as $repo) {
                $records[] = [
                    $repo['owner'],
                    $repo['project'],
                    $repo['stars']
                ];
            }

            $csv = Writer::createFromString('', 'w');

            $csv->setDelimiter(';');
            $csv->setNewline("\r\n");
            $encoder = (new CharsetConverter())
                ->inputEncoding('utf-8')
                ->outputEncoding('iso-8859-15');
            $csv->addFormatter($encoder);

            $csv->insertOne($header);
            $csv->insertAll($records);
            $csv->output(sprintf('repositories_report_%s.csv', date("Y-m-d")));
            exit;
        }

        return new JsonResponse(['data' => $data]);
    }
}
