<?php

namespace Tests\Unit\Commands;

use App\Mail\AvailableSlots;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class GetAvailableBookingTest extends TestCase
{
    /** @test **/
    public function sends_email_when_available_time_slots_are_found(): void
    {
        $availabilityResponse = File::get(__DIR__ . '/../../responses/successfulAvailability.json');
        $timeSlotResponse = File::get(__DIR__ . '/../../responses/successfulTimeSlots.json');

        Http::fake([
            '/book-online/availability/*' => Http::response($availabilityResponse),
            '/book-online/new/availability/appointment/*' => Http::response($timeSlotResponse)
        ]);

        Mail::fake();

        Carbon::setTestNow('2022-03-01');

        Config::set('slick.lookout_weeks', 1);
        Config::set('slick.email', 'test@example.com');

        $this->artisan('get:booking')->assertSuccessful();

        Mail::assertSent(AvailableSlots::class, function(AvailableSlots $mail) {
            return $mail->hasTo('test@example.com') &&
                $mail->slots->count() === 1 &&
                $mail->slots->first()->dateTime->eq(Carbon::parse('2022-03-02 6pm'));
        });
    }
}
