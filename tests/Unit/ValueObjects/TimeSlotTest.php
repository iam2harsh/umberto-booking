<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class TimeSlotTest extends TestCase
{
    /** @test */
    public function can_be_constructed(): void
    {
        Carbon::setTestNow('2022-01-01');

        $timeSlot = new TimeSlot(now());

        $this->assertTrue($timeSlot->dateTime->eq(now()));
    }

    /** @test */
    public function is_available_when_time_slot_is_after_set_hour(): void
    {
        Carbon::setTestNow('2022-01-01 2pm');
        Config::set('slick.available_after_hour', 12);

        $timeSlot = new TimeSlot(now());

        $this->assertTrue($timeSlot->isAvailable());
    }

    /** @test */
    public function is_not_available_when_time_slot_is_before_set_hour(): void
    {
        Carbon::setTestNow('2022-01-01 11am');
        Config::set('slick.available_after_hour', 12);

        $timeSlot = new TimeSlot(now());

        $this->assertFalse($timeSlot->isAvailable());
    }
}
