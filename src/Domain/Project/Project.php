<?php

declare(strict_types=1);

namespace App\Domain\Project;

class Project
{
    public function __construct(
        private string $name,
        private \DateTime $startDate,
        private \DateTime $endDate,
        private int $profitability
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function startDate(): \DateTime
    {
        return $this->startDate;
    }

    public function endDate(): \DateTime
    {
        return $this->endDate;
    }

    public function profitability(): int
    {
        return $this->profitability;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'startDate' => $this->startDate->format('d/m/Y'),
            'endDate' => $this->endDate->format('d/m/Y'),
            'profitability' => $this->profitability,
        ];
    }
}
