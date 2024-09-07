<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Availability;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    #[Test]
    public function can_be_constructed(): void
    {
        Carbon::setTestNow('2022-01-01');

        $availability = new Availability(now(), true);

        $this->assertTrue($availability->date->eq(now()));
        $this->assertTrue($availability->isAvailable);
    }
}
