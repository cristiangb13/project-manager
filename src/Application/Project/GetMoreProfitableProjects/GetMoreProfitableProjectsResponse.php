<?php

declare(strict_types=1);

namespace App\Application\Project\GetMoreProfitableProjects;

use App\Domain\Project\ProjectCollection;

class GetMoreProfitableProjectsResponse
{
    public function __construct(private ProjectCollection $projectCollection)
    {
    }

    public function response(): array
    {
        return [
            'projects' => $this->projectCollection->toArray(),
            'profitability' => $this->projectCollection->profitability(),
        ];
    }
}
