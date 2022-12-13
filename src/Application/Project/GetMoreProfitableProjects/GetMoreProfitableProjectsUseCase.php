<?php

declare(strict_types=1);

namespace App\Application\Project\GetMoreProfitableProjects;

use App\Domain\Project\Service\MoreProfitableProjectsFinder;

class GetMoreProfitableProjectsUseCase
{
    public function __construct(private MoreProfitableProjectsFinder $moreProfitableProjectsFinder)
    {
    }

    public function handle(): GetMoreProfitableProjectsResponse
    {
        $projects = $this->moreProfitableProjectsFinder->find();

        return new GetMoreProfitableProjectsResponse($projects);
    }
}
