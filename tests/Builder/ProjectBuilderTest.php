<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Project\Project;

class ProjectBuilderTest
{
    public function __construct(
        private string $name,
        private \DateTime $startDate,
        private \DateTime $endDate,
        private int $profitability
    ) {
    }

    public static function create(): ProjectBuilderTest
    {
        return new self(
            'name',
            new \DateTime('today'),
            (new \DateTime('today'))->modify('+ 3 day'),
            10000
        );
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function withEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function withProfitability(int $profitability): self
    {
        $this->profitability = $profitability;

        return $this;
    }

    public function build(): Project
    {
        return new Project(
            $this->name,
            $this->startDate,
            $this->endDate,
            $this->profitability,
        );
    }
}
