<?php

namespace Tests\Unit;

use App\Models\StudentBatch;
use Mockery;
use PHPUnit\Framework\TestCase;

class StudentBatchTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_assigned_on_uses_historical_dates_without_current_status(): void
    {
        $query = Mockery::mock();
        $query->shouldReceive('whereDate')
            ->once()
            ->with('start_date', '<=', '2026-10-01')
            ->andReturnSelf();
        $query->shouldReceive('whereDate')
            ->once()
            ->with('end_date', '>=', '2026-10-01')
            ->andReturnSelf();

        $result = (new StudentBatch())->scopeAssignedOn($query, '1 Oct 2026');

        $this->assertSame($query, $result);
    }
}
