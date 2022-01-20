<?php

namespace App\ValueObjects;

use Carbon\Carbon;

class AvailableSlot
{
    public function __construct(
        public Carbon $dateTime,
    ) {}
}