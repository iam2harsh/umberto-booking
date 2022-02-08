<?php

namespace Tests\Unit\Kernel;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class KernelTest extends TestCase
{

    /** @test */
    public function testIsAvailableInTheScheduler()
    {
        $schedule = app()->make(Schedule::class);

        $events = collect($schedule->events())->filter(function (Event $event) {
            return stripos($event->command, 'get:booking');
        });

        if ($events->count() == 0) {
            $this->fail('No events found');
        }

        $events->each(function (Event $event) {
            $this->assertEquals('0,30 * * * *', $event->expression);
        });
    }
}
