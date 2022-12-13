<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Persistence;

use App\Domain\Project\Project;
use App\Domain\Project\ProjectCollection;
use App\Domain\Project\ProjectRepositoryInterface;

class InMemoryProjectRepository implements ProjectRepositoryInterface
{
    private array $projects;

    public function __construct()
    {
        $this->projects = [
            new Project(
                'MOLINA',
                \DateTime::createFromFormat('d/m/Y', '01/01/2022'),
                \DateTime::createFromFormat('d/m/Y', '15/01/2022'),
                14000
            ),
            new Project(
                'TENERIFE',
                \DateTime::createFromFormat('d/m/Y', '04/01/2022'),
                \DateTime::createFromFormat('d/m/Y', '07/01/2022'),
                7000
            ),
            new Project(
                'ARTURO',
                \DateTime::createFromFormat('d/m/Y', '07/01/2022'),
                \DateTime::createFromFormat('d/m/Y', '24/01/2022'),
                19000
            ),
            new Project('MIJAS',
                \DateTime::createFromFormat('d/m/Y', '15/01/2022'),
                \DateTime::createFromFormat('d/m/Y', '31/01/2022'),
                18000
            ),
        ];
    }

    public function all(): ProjectCollection
    {
        return new ProjectCollection(...$this->projects);
    }
}
