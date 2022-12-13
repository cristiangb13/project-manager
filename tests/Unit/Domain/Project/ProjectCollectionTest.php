<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Project;

use App\Domain\Project\ProjectCollection;
use App\Tests\Builder\ProjectBuilderTest;
use PHPUnit\Framework\TestCase;

class ProjectCollectionTest extends TestCase
{
    public function testProfitability(): void
    {
        $projects = [
            ProjectBuilderTest::create()->withProfitability(5000)->build(),
            ProjectBuilderTest::create()->withProfitability(10000)->build(),
            ProjectBuilderTest::create()->withProfitability(2000)->build(),
        ];

        $sut = new ProjectCollection(...$projects);

        self::assertEquals(17000, $sut->profitability());
    }

    /** @dataProvider projectDataProvider */
    public function testMoreProfitableProjectsWithoutOverlappingDates(
        ProjectCollection $projectCollection,
        ProjectCollection $expectedMoreProfitableProjectCollection
    ): void {
        self::assertEquals(
            $expectedMoreProfitableProjectCollection,
            $projectCollection->moreProfitableProjectsWithoutOverlappingDates()
        );
    }

    public function projectDataProvider(): array
    {
        return [
            'empty collection' => [
                'projectCollection' => new ProjectCollection(...[]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[]),
            ],
            'one project' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->build(),
                ]),
            ],
            'two projects, overlapping dates, first most profitable' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->withProfitability(5000)->build(),
                    ProjectBuilderTest::create()->withProfitability(2000)->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->withProfitability(5000)->build(),
                ]),
            ],
            'two projects, overlapping dates, second most profitable' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->withProfitability(2000)->build(),
                    ProjectBuilderTest::create()->withProfitability(5000)->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()->withProfitability(5000)->build(),
                ]),
            ],
            'technical test example' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withName('MOLINA')
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(14000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('TENERIFE')
                        ->withStartDate(new \DateTime('2022-01-04'))
                        ->withEndDate(new \DateTime('2022-01-07'))
                        ->withProfitability(7000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('ARTURO')
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-24'))
                        ->withProfitability(19000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('MIJAS')
                        ->withStartDate(new \DateTime('2022-01-15'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(18000)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withName('MOLINA')
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(14000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('MIJAS')
                        ->withStartDate(new \DateTime('2022-01-15'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(18000)
                        ->build(),
                ]),
            ],
            'complex example 1' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-04'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-06'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-06'))
                        ->withEndDate(new \DateTime('2022-01-08'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-08'))
                        ->withEndDate(new \DateTime('2022-01-12'))
                        ->withProfitability(1000)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-04'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-06'))
                        ->withEndDate(new \DateTime('2022-01-08'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-08'))
                        ->withEndDate(new \DateTime('2022-01-12'))
                        ->withProfitability(1000)
                        ->build(),
                ]),
            ],
            'complex example 2' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-04'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-06'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-06'))
                        ->withEndDate(new \DateTime('2022-01-08'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(3000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-08'))
                        ->withEndDate(new \DateTime('2022-01-12'))
                        ->withProfitability(1000)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-06'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(3000)
                        ->build(),
                ]),
            ],
            'complex example 3' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-04'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-06'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-06'))
                        ->withEndDate(new \DateTime('2022-01-08'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(3000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-08'))
                        ->withEndDate(new \DateTime('2022-01-12'))
                        ->withProfitability(1000)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-06'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(3000)
                        ->build(),
                ]),
            ],
            'complex case 3' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-04'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(750)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-15'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(750)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-07'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(750)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withStartDate(new \DateTime('2022-01-15'))
                        ->withEndDate(new \DateTime('2022-01-31'))
                        ->withProfitability(750)
                        ->build(),
                ]),
            ],
            'readme example' => [
                'projectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withName('Alfa')
                        ->withStartDate(new \DateTime('2022-01-01'))
                        ->withEndDate(new \DateTime('2022-01-04'))
                        ->withProfitability(1000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('Beta')
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-05'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('Charlie')
                        ->withStartDate(new \DateTime('2022-01-05'))
                        ->withEndDate(new \DateTime('2022-01-10'))
                        ->withProfitability(3000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('Delta')
                        ->withStartDate(new \DateTime('2022-01-05'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(5000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('Echo')
                        ->withStartDate(new \DateTime('2022-01-12'))
                        ->withEndDate(new \DateTime('2022-01-30'))
                        ->withProfitability(1000)
                        ->build(),
                ]),
                'expectedMoreProfitableProjectCollection' => new ProjectCollection(...[
                    ProjectBuilderTest::create()
                        ->withName('Beta')
                        ->withStartDate(new \DateTime('2022-01-02'))
                        ->withEndDate(new \DateTime('2022-01-05'))
                        ->withProfitability(2000)
                        ->build(),
                    ProjectBuilderTest::create()
                        ->withName('Delta')
                        ->withStartDate(new \DateTime('2022-01-05'))
                        ->withEndDate(new \DateTime('2022-01-15'))
                        ->withProfitability(5000)
                        ->build(),
                ]),
            ],
        ];
    }
}
