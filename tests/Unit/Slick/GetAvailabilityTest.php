<?php

namespace Tests\Unit\Slick;

use App\Exceptions\FailedToGetAvailability;
use App\Slick;
use App\ValueObjects\Availability;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GetAvailabilityTest extends TestCase
{
    /** @test **/
    public function handles_successful_response(): void
    {
        $response = File::get(__DIR__ . '/../../responses/successfulAvailability.json');

        Http::fake([
            '/book-online/availability/*' => Http::response($response, 200)
        ]);

        $results = (new Slick)->getAvailability(Carbon::now());

        $this->assertCount(7, $results);
        $results->each(function (Availability $availability) {
            $this->assertInstanceOf(Availability::class, $availability);
            $this->assertInstanceOf(Carbon::class, $availability->date);
            $this->assertIsBool($availability->isAvailable);
        });
    }

    /** @test **/
    public function handles_no_content_response(): void
    {
        $this->expectException(FailedToGetAvailability::class);

        Http::fake([
            '/book-online/availability/*' => Http::response(null, 204),
        ]);

        (new Slick)->getAvailability(Carbon::now());
    }

    /** @test **/
    public function handles_error_response(): void
    {
        $this->expectException(FailedToGetAvailability::class);

        Http::fake([
            '/book-online/availability/*' => Http::response(null, 500),
        ]);

        (new Slick)->getAvailability(Carbon::now());
    }
}
