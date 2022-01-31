<?php

namespace Tests\Unit\Slick;

use App\Exceptions\FailedToGetTimeSlot;
use App\Slick;
use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GetAvailableSlotsTest extends TestCase
{
    /** @test **/
    public function handles_successful_response(): void
    {
        $response = File::get(__DIR__ . '/../../responses/successfulTimeSlots.json');

        Http::fake([
            '/book-online/new/availability/appointment/*' => Http::response($response, 200)
        ]);

        $results = (new Slick)->getAvailableSlots(Carbon::now());

        $this->assertCount(7, $results);
        $results->each(function (TimeSlot $timeSlot) {
            $this->assertInstanceOf(TimeSlot::class, $timeSlot);
            $this->assertInstanceOf(Carbon::class, $timeSlot->dateTime);
        });
    }

    /** @test **/
    public function handles_no_content_response(): void
    {
        $this->expectException(FailedToGetTimeSlot::class);

        Http::fake([
            '/book-online/new/availability/appointment/*' => Http::response(null, 204),
        ]);

        (new Slick)->getAvailableSlots(Carbon::now());
    }

    /** @test **/
    public function handles_error_response(): void
    {
        $this->expectException(FailedToGetTimeSlot::class);

        Http::fake([
            '/book-online/new/availability/appointment/*' => Http::response(null, 500),
        ]);

        (new Slick)->getAvailableSlots(Carbon::now());
    }
}
