<?php

namespace App\ValueObjects;

use Carbon\Carbon;

class Availability
{
    public function __construct(
        public Carbon $date,
        public bool $isAvailable
    ) {}
}