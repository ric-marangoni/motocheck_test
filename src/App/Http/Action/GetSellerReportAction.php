<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Application\Service\GitHubService;
use League\Csv\CharsetConverter;
use League\Csv\Reader;
use League\Csv\Writer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetSellerReportAction implements RequestHandlerInterface
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
        $sellersData = Reader::createFromPath('data/sales-team.csv')->setHeaderOffset(0);
        $records = Reader::createFromPath('data/sales-records.csv')->setHeaderOffset(0);

        $sellers = [];
        foreach ($sellersData as $seller) {
            $sellers[$seller['seller']] = [
                'date' => \DateTime::createFromFormat('d/m/Y', $seller['date'])
            ];
        }

        foreach ($records as $record) {
            $sellers[$record['name']]['total_amount'] += (int)$record['quantity'] * (float)$record['unitary_value'];
        }

        foreach ($sellers as $seller => $data) {
            $commission = 7;
            $now = new \DateTime('now');
            $diff = $now->diff($data['date']);

            if ($data['total_amount'] >= 200000) {
                $commission = 8;
            }

            if ($data['total_amount'] > 1000000) {
                $commission = 11;
            }

            if ($data['total_amount'] > 2000000) {
                $commission = 17;
            }

            if ($diff->y < 1 && $commission > 11) {
                $commission = 11;
            }

            if ($diff->y > 2 && $commission < 15) {
                $commission = 15;
            }

            $sellers[$seller]['date'] = $data['date']->format('Y-m-d');
            $sellers[$seller]['percent'] = $commission;
            $sellers[$seller]['commission'] = $data['total_amount'] * ($commission / 100);
        }

        $header = [
            'Name',
            'Date',
            'Total amount',
            'Percent',
            'Commission'
        ];

        $records = [];

        foreach ($sellers as $seller => $data) {
            $records[] = [
                $seller,
                $data['date'],
                $data['total_amount'],
                $data['percent'],
                $data['commission']

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
        $csv->output(sprintf('seller_report_%s.csv', date("Y-m-d")));
        exit;
    }
}
