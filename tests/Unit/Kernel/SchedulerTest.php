<?php

namespace Tests\Unit\Kernel;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SchedulerTest extends TestCase
{

    #[Test]
    public function check_if_scheduler_is_running_the_get_booking_command_every_30_mins(): void
    {
        $schedule = app()->make(Schedule::class);

        $events = collect($schedule->events())
            ->filter(function (Event $event) {
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
