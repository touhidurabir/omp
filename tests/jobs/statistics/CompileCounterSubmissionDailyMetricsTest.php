<?php

/**
 * @file tests/jobs/statistics/CompileCounterSubmissionDailyMetricsTest.php
 *
 * Copyright (c) 2024 Simon Fraser University
 * Copyright (c) 2024 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @brief Tests for compile counter submission daily metrics job.
 */

namespace APP\tests\jobs\statistics;

use APP\jobs\statistics\CompileCounterSubmissionDailyMetrics;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PKP\db\DAORegistry;
use PKP\tests\PKPTestCase;

#[RunTestsInSeparateProcesses]
#[CoversClass(CompileCounterSubmissionDailyMetrics::class)]
class CompileCounterSubmissionDailyMetricsTest extends PKPTestCase
{
    /**
     * base64_encoded serializion from OMP 3.4.0
     */
    protected string $serializedJobData = <<<END
    O:56:"APP\jobs\statistics\CompileCounterSubmissionDailyMetrics":3:{s:9:"\0*\0loadId";s:25:"usage_events_20240130.log";s:10:"connection";s:8:"database";s:5:"queue";s:5:"queue";}
    END;

    /**
     * Test job is a proper instance
     */
    public function testUnserializationGetProperDepositIssueJobInstance(): void
    {
        $this->assertInstanceOf(
            CompileCounterSubmissionDailyMetrics::class,
            unserialize($this->serializedJobData)
        );
    }

    /**
     * Ensure that a serialized job can be unserialized and executed
     */
    public function testRunSerializedJob(): void
    {
        /** @var CompileCounterSubmissionDailyMetrics $compileCounterSubmissionDailyMetricsJob */
        $compileCounterSubmissionDailyMetricsJob = unserialize($this->serializedJobData);

        $temporaryTotalsDAOMock = Mockery::mock(\APP\statistics\TemporaryTotalsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'deleteCounterSubmissionDailyByLoadId' => null,
                'compileCounterSubmissionDailyMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryTotalsDAO', $temporaryTotalsDAOMock);

        $temporaryItemInvestigationsDAOMock = Mockery::mock(\APP\statistics\TemporaryItemInvestigationsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'compileCounterSubmissionDailyMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryItemInvestigationsDAO', $temporaryItemInvestigationsDAOMock);

        $temporaryItemRequestsDAOMock = Mockery::mock(\APP\statistics\TemporaryItemRequestsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'compileCounterSubmissionDailyMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryItemRequestsDAO', $temporaryItemRequestsDAOMock);

        $temporaryTitleInvestigationsDAOMock = Mockery::mock(\APP\statistics\TemporaryTitleInvestigationsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'compileCounterSubmissionDailyMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryTitleInvestigationsDAO', $temporaryTitleInvestigationsDAOMock);

        $temporaryTitleRequestsDAO = Mockery::mock(\APP\statistics\TemporaryTitleRequestsDAO::class)
            ->makePartial()
            ->shouldReceive([
                'compileCounterSubmissionDailyMetrics' => null,
            ])
            ->withAnyArgs()
            ->getMock();

        DAORegistry::registerDAO('TemporaryTitleRequestsDAO', $temporaryTitleRequestsDAO);

        $compileCounterSubmissionDailyMetricsJob->handle();

        $this->expectNotToPerformAssertions();
    }
}
