<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Http;

use App\Application\Project\GetMoreProfitableProjects\GetMoreProfitableProjectsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetMoreProfitableProjectsController
{
    public function __construct(private GetMoreProfitableProjectsUseCase $useCase)
    {
    }

    public function __invoke(): JsonResponse
    {
        $response = $this->useCase->handle();

        return new JsonResponse($response->response());
    }
}
