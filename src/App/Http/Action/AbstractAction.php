<?php
declare(strict_types=1);

namespace App\Http\Action;

use App\Application\Helper\EnvironmentHelper;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class AbstractAction
{
    /**
     * @param $isException
     * @param null|\Throwable $exception
     * @return int
     */
    private function getCodeException($isException, $exception = null): int
    {
        if (!$isException) {
            return 400;
        }

        return $exception->getCode() ?: 500;
    }

    /**
     * @param string $message
     * @param null $detailed
     * @return JsonResponse
     */
    protected function methodBadRequest($message = 'Invalid body', $detailed = null): JsonResponse
    {
        $isException = ($detailed instanceof \Exception || $detailed instanceof \Throwable);
        if (null !== $detailed && EnvironmentHelper::isProduction() === false) {
            $message = $isException
                ? sprintf(
                    '%s. Line %s. File %s',
                    $detailed->getMessage(),
                    $detailed->getLine(),
                    $detailed->getFile()
                )
                : $message;
        }

        return $this->jsonResponse(
            [
                'status' => false,
                'message' => $message
            ],
            $this->getCodeException($isException, $detailed)
        );
    }

    /**
     * @return JsonResponse
     */
    protected function methodNotAllowed(): JsonResponse
    {
        return $this->jsonResponse(
            [
                'status' => false,
                'message' => 'Method not allowed'
            ],
            405
        );
    }

    /**
     * @return JsonResponse
     */
    protected function methodUnprocessableEntity(): JsonResponse
    {
        return $this->jsonResponse(
            [
                'status' => false,
                'message' => 'Inappropriate or unable to process'
            ],
            422
        );
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse(array $data, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse($data, $statusCode);
    }
}