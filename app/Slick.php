<?php

namespace App;

use App\Exceptions\FailedToGetAvailability;
use App\Exceptions\FailedToGetTimeSlot;
use Carbon\Carbon;
use App\ValueObjects\Availability;
use App\ValueObjects\TimeSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Slick
{
    public function __construct(
        public string $baseUri = 'https://core.api.getslick.com/book-online/'
    ) {}

    public function getAvailability(Carbon $start): Collection
    {
        $response = Http::get(
            Str::of($this->baseUri)
                ->append('availability/weekly/2650/92546/10821/')
                ->append($start->format('Y-m-d\TH:i:s'))
                ->append('.000Z/1/')
        );

        throw_if($response->failed(), FailedToGetAvailability::class);

        return $response
            ->collect()
            ->map(function ($available, $date) {
                return new Availability(Carbon::parse($date), $available);
            })
            ->flatten();        
    }

    public function getAvailableSlots(Carbon $date): Collection
    {
        $response = Http::get(
            Str::of($this->baseUri)
                ->append('new/availability/appointment/2650/')
                ->append($date->format('Y-m-d\TH:i:s'))
                ->append('.000Z/92546/10821/')
        );

        throw_if($response->failed(), FailedToGetTimeSlot::class);

        return collect($response->object()->data)
            ->map(function ($data, $slot) {
                return new TimeSlot(Carbon::parse($slot));
            })
            ->flatten();        
    }
}