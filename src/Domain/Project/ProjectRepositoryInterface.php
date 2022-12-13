<?php

declare(strict_types=1);

namespace App\Domain\Project;

interface ProjectRepositoryInterface
{
    public function all(): ProjectCollection;
}
