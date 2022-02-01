<?php

namespace Tests\Unit\Slick;

use App\Slick;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GetWeeklyAvailabilityTest extends TestCase
{
    /** @test **/
    public function repeats_twice_for_two_weeks(): void
    {
        $response = File::get(__DIR__ . '/../../responses/successfulAvailability.json');

        Http::fake([
            '/book-online/availability/*' => Http::response($response, 200)
        ]);

        $results = (new Slick)->getWeeklyAvailability(Carbon::now(), 2);

        $this->assertCount(14, $results);
    }

    /** @test **/
    public function once_if_weeks_is_not_set(): void
    {
        $response = File::get(__DIR__ . '/../../responses/successfulAvailability.json');

        Http::fake([
            '/book-online/availability/*' => Http::response($response, 200)
        ]);

        $results = (new Slick)->getWeeklyAvailability(Carbon::now());

        $this->assertCount(7, $results);
    }
}
