<?php

namespace App\ValueObjects;

use Carbon\Carbon;

class TimeSlot
{
    public function __construct(
        public Carbon $dateTime,
    ) {}

    public function isAvailable(): bool
    {
        return $this->dateTime->isAfter(
            $this->dateTime->clone()->setTime(config('slick.available_after_hour', 17),0)
        );
    }
}