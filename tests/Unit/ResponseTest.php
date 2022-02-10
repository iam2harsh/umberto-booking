<?php

namespace Tests\Unit;

use App\Exceptions\FailedToGetAvailability;
use App\Exceptions\FailedToGetTimeSlot;
use App\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResponseTest extends TestCase
{

    /** @test */
    public function can_handle_get_availability_no_content(): void
    {
        $this->expectException(FailedToGetAvailability::class);

        Http::fake(fn () => Http::response(null, 204));

        $response = Http::get('');

        Response::handle($response, FailedToGetAvailability::class);
    }

    /** @test */
    public function can_handle_get_time_slot_no_content(): void
    {
        $this->expectException(FailedToGetTimeSlot::class);

        Http::fake(fn () => Http::response(null, 204));

        $response = Http::get('');

        Response::handle($response, FailedToGetTimeSlot::class);
    }

    /** @test */
    public function can_handle_get_availability_bad_request(): void
    {
        $this->expectException(FailedToGetAvailability::class);

        Http::fake(fn () => Http::response(null, 400));

        $response = Http::get('');

        Response::handle($response, FailedToGetAvailability::class);
    }

    /** @test */
    public function can_handle_get_time_slot_bad_request(): void
    {
        $this->expectException(FailedToGetTimeSlot::class);

        Http::fake(fn () => Http::response(null, 400));

        $response = Http::get('');

        Response::handle($response, FailedToGetTimeSlot::class);
    }

    /** @test */
    public function can_handle_get_availability_error(): void
    {
        $this->expectException(FailedToGetAvailability::class);

        Http::fake(fn () => Http::response(null, 500));

        $response = Http::get('');

        Response::handle($response, FailedToGetAvailability::class);
    }

    /** @test */
    public function can_handle_get_time_slot_error(): void
    {
        $this->expectException(FailedToGetTimeSlot::class);

        Http::fake(fn () => Http::response(null, 500));

        $response = Http::get('');

        Response::handle($response, FailedToGetTimeSlot::class);
    }
}
