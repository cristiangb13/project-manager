<?php

declare(strict_types=1);

namespace App\Domain\Project;

class ProjectCollection
{
    private array $projects;

    public function __construct(Project ...$projects)
    {
        $this->projects = $projects;
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->projects as $project) {
            $data[] = $project->toArray();
        }

        return $data;
    }

    public function profitability(): int
    {
        $profitability = 0;

        foreach ($this->projects as $project) {
            $profitability += $project->profitability();
        }

        return $profitability;
    }

    public function moreProfitableProjectsWithoutOverlappingDates(): ProjectCollection
    {
        $this->orderByStartDate();

        $projectCombinations = $this->findAllProjectCombinationsWithoutOverlappingDates($this->projects);

        $bestCombination = new ProjectCollection(...[]);
        $bestProfitability = 0;

        foreach ($projectCombinations as $projectCombination) {
            $projectCombination = new ProjectCollection(...$projectCombination);
            $data[] = $projectCombination->toArray();

            if ($projectCombination->profitability() > $bestProfitability) {
                $bestCombination = $projectCombination;
                $bestProfitability = $projectCombination->profitability();
            }
        }

        return $bestCombination;
    }

    private function orderByStartDate(): void
    {
        usort($this->projects, static function (Project $aProject, Project $anotherProject) {
            return $aProject->startDate()->getTimestamp() - $anotherProject->startDate()->getTimestamp();
        });
    }

    private function findAllProjectCombinationsWithoutOverlappingDates(
        array $projects,
        bool $isFirstIteration = true
    ): array {
        /** @var Project[] $validCombinations */
        $validCombinations = [];
        $pendingCombinations = [];

        /** @var Project $currentProject */
        foreach ($projects as $index => $currentProject) {
            if (empty($validCombinations)) {
                $validCombinations[] = $currentProject;

                continue;
            }

            $lastProject = $validCombinations[count($validCombinations) - 1];
            if ($currentProject->startDate() >= $lastProject->endDate()) {
                $validCombinations[] = $currentProject;

                continue;
            }

            $secondLastProject = $validCombinations[count($validCombinations) - 2] ?? null;
            if (null !== $secondLastProject && $currentProject->startDate() >= $secondLastProject->endDate()) {
                $copyValidCombinations = $validCombinations;
                $copyValidCombinations[count($validCombinations) - 1] = $currentProject;

                $pendingCombinations[] = $this->findAllProjectCombinationsWithoutOverlappingDates(
                    array_merge($copyValidCombinations, array_slice($projects, $index + 1)),
                    false
                );

                continue;
            }

            if ($isFirstIteration) {
                $pendingCombinations[] = $this->findAllProjectCombinationsWithoutOverlappingDates(
                    array_slice($projects, $index),
                    false
                );
            }
        }

        return array_merge([$validCombinations], ...$pendingCombinations);
    }
}
