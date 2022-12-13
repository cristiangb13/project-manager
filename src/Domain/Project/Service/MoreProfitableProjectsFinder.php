<?php

declare(strict_types=1);

namespace App\Domain\Project\Service;

use App\Domain\Project\ProjectCollection;
use App\Domain\Project\ProjectRepositoryInterface;

class MoreProfitableProjectsFinder
{
    public function __construct(private ProjectRepositoryInterface $projectRepository)
    {
    }

    public function find(): ProjectCollection
    {
        $projects = $this->projectRepository->all();

        return $projects->moreProfitableProjectsWithoutOverlappingDates();
    }
}
