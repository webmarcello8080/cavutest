<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Services\BookingPriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase; // Resets the database between tests

    protected $bookingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = $this->mock(BookingPriceService::class);
    }

    /** @test */
    public function it_creates_a_booking_when_spaces_are_available()
    {
        $this->bookingService->shouldReceive('isDateRangeAvailable')
            ->once()
            ->with('2025-04-01', '2025-04-05')
            ->andReturn(true);

        $this->bookingService->shouldReceive('calculatePrice')
            ->once()
            ->with('2025-04-01', '2025-04-05')
            ->andReturn(100.0);

        $response = $this->postJson('/api/create-booking', [
            'customer' => 'Mike',
            'from' => '2025-04-01',
            'to' => '2025-04-05',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'booking' => ['id', 'customer', 'price', 'from', 'to', 'created_at', 'updated_at'],
            ]);
    }

    /** @test */
    public function it_fails_to_create_a_booking_when_spaces_are_not_available()
    {
        $this->bookingService->shouldReceive('isDateRangeAvailable')
            ->once()
            ->with('2025-04-01', '2025-04-05')
            ->andReturn(false);

        $response = $this->postJson('/api/create-booking', [
            'customer' => 'Jane Doe',
            'from' => '2025-04-01',
            'to' => '2025-04-05',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'No parking spaces available for the selected date range.',
            ]);
    }

    /** @test */
    public function it_updates_a_booking_when_spaces_are_available()
    {
        $booking = Booking::factory()->create([
            'customer' => 'Jane Doe',
            'from' => '2025-04-01',
            'to' => '2025-04-05',
            'price' => 100.0,
        ]);

        $this->bookingService->shouldReceive('isDateRangeAvailable')
            ->once()
            ->with('2025-04-06', '2025-04-10')
            ->andReturn(true);

        $response = $this->putJson("/api/update-booking/{$booking->id}", [
            'customer' => 'Jane Doe',
            'from' => '2025-04-06',
            'to' => '2025-04-10',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Booking updated successfully',
            ]);
    }

    /** @test */
    public function it_fails_to_update_a_booking_when_spaces_are_not_available()
    {
        $booking = Booking::factory()->create([
            'customer' => 'Mike',
            'from' => '2025-04-01',
            'to' => '2025-04-05',
            'price' => 100.0,
        ]);

        $this->bookingService->shouldReceive('isDateRangeAvailable')
            ->once()
            ->with('2025-04-06', '2025-04-10')
            ->andReturn(false);

        $response = $this->putJson("/api/update-booking/{$booking->id}", [
            'customer' => 'Mike',
            'from' => '2025-04-06',
            'to' => '2025-04-10',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'No parking spaces available for the selected date range.',
            ]);
    }
}
