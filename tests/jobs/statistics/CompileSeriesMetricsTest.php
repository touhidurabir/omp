<?php

/**
 * @file tests/jobs/statistics/CompileCounterSubmissionInstitutionDailyMetricsTest.php
 *
 * Copyright (c) 2024 Simon Fraser University
 * Copyright (c) 2024 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @brief Tests for compile series metrics job.
 */

namespace APP\tests\jobs\statistics;

use APP\jobs\statistics\CompileSeriesMetrics;
use Mockery;
use PKP\db\DAORegistry;
use PKP\tests\PKPTestCase;

/**
 * @runTestsInSeparateProcesses
 *
 * @see https://docs.phpunit.de/en/9.6/annotations.html#runtestsinseparateprocesses
 */
class CompileSeriesMetricsTest extends PKPTestCase
{
    /**
     * base64_encoded serializion from OMP 3.4.0
     */
    protected string $serializedJobData = <<<END
    O:40:"APP\jobs\statistics\CompileSeriesMetrics":3:{s:9:"\0*\0loadId";s:25:"usage_events_20240130.log";s:10:"connection";s:8:"database";s:5:"queue";s:5:"queue";}
    END;

    /**
     * Test job is a proper instance
     */
    public function testUnserializationGetProperDepositIssueJobInstance(): void
    {
        $this->assertInstanceOf(
            CompileSeriesMetrics::class,
            unserialize($this->serializedJobData)
        );
    }

    /**
     * Ensure that a serialized job can be unserialized and executed
     */
    public function testRunSerializedJob()
    {
        /** @var CompileSeriesMetrics $compileSeriesMetricsJob */
        $compileSeriesMetricsJob = unserialize($this->serializedJobData);

        $temporaryTotalsDAOMock = Mockery::mock(\APP\statistics\TemporaryTotalsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'compileSeriesMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryTotalsDAO', $temporaryTotalsDAOMock);


        $this->assertNull($compileSeriesMetricsJob->handle());
    }
}
