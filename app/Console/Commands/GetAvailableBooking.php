<?php

namespace App\Console\Commands;

use App\Exceptions\FailedToGetAvailability;
use App\Exceptions\FailedToGetTimeSlot;
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

    protected $description = 'Send email when eligible booking time slots are found';

    public function handle(Slick $slick): void
    {
        try {
            $slick
                ->getAvailability(Carbon::tomorrow()->startOfDay())
                ->filter(function ($availability) {
                    return $availability->isAvailable &&
                        $availability->date->isBefore(
                            Carbon::now()->startOfDay()->addWeeks(config('slick.lookout_weeks', 2))
                        );
                })
                ->transform(function (Availability $availability) use ($slick) {
                    return $slick
                        ->getAvailableSlots($availability->date)
                        ->filter(function (TimeSlot $slot): bool {
                            return $slot->isAvailable();
                        });
                })
                ->whenNotEmpty(function(Collection $finalSlots) {
                    Mail::to(config('slick.email'))
                        ->send(new AvailableSlots($finalSlots->flatten()));
                });
        }
        catch (FailedToGetAvailability $exception)
        {
            report($exception);
        }
        catch (FailedToGetTimeSlot $exception)
        {
            report($exception);
        }
    }
}