<?php

namespace Tests\Unit\Mail;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Mail\AvailableSlots;
use App\ValueObjects\TimeSlot;

class AvailableSlotTest extends TestCase
{

    #[Test]
    public function view_data_matches_data_passed_to_mail_class(): void
    {
        $slots = collect([
            new TimeSlot(now()),
        ]);

        $availableSlots = new AvailableSlots($slots);

        $this->assertSame($slots, $availableSlots->build()->viewData['slots']);
    }
}
