<?php

namespace App\Console\Commands;

use App\Mail\AvailableSlots;
use App\Slick;
use App\ValueObjects\Availability;
use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class GetAvailableBooking extends Command
{
    protected $signature = 'get:booking';

    protected $description = 'Get booking for a date range';

    public function handle()
    {
        $slick = new Slick;

        $availability = $slick->getAvailability(Carbon::tomorrow()->startOfDay());

        $end = Carbon::now()->startOfDay()->addWeeks(config('slick.lookout_weeks', 2));

        $finalSlots = collect();

        $availability->each(function (Availability $availability) use ($end, &$finalSlots, $slick) {
            if ($availability->isAvailable && $availability->date->isBefore($end)) {
                $finalSlots
                    ->add(
                        $slick
                        ->getAvailableSlots($availability->date)
                        ->filter(function (TimeSlot $slot) {
                            return $slot->isAvailable();
                        })
                    );
            }
        });

        if ($finalSlots->isNotEmpty()) {
            $this->sendEmail($finalSlots);
        }
    }

    private function sendEmail(Collection $slots)
    {
        Mail::to(config('slick.email'))
            ->send(new AvailableSlots($slots->flatten()));
    }
}