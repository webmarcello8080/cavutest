<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAvailabilityRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingPriceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{

    protected BookingPriceService $bookingService;

    public function __construct(BookingPriceService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * create a new booking
     * 
     * @param  StoreBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(StoreBookingRequest $request): JsonResponse
    {
        // Check parking space availability
        if (!$this->bookingService->isDateRangeAvailable($request->from, $request->to)) {
            return response()->json([
                'message' => "No parking spaces available for the selected date range.",
            ], 422);
        }

        $price = $this->bookingService->calculatePrice($request->from, $request->to);

        $booking = Booking::create([
            'customer' => $request->customer,
            'price' => $price,
            'from' => $request->from,
            'to' => $request->to,
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * Check available parking spaces for a given date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(CheckAvailabilityRequest $request): JsonResponse
    {
        $availability = $this->bookingService->getAvailableSpaces($request->from, $request->to);
    
        return response()->json([
            'message' => 'Availability retrieved successfully',
            'availability' => $availability,
        ], 200);
    }

    /**
     * Update an existing booking.
     *
     * @param  \App\Http\Requests\StoreBookingRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreBookingRequest $request, $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);

            // Check parking space availability
            if (!$this->bookingService->isDateRangeAvailable($request->from, $request->to)) {
                return response()->json([
                    'message' => "No parking spaces available for the selected date range.",
                ], 422);
            }

            // Update the booking with new data
            $booking->update([
                'customer' => $request->customer,
                'from' => $request->from,
                'to' => $request->to,
            ]);

            return response()->json([
                'message' => 'Booking updated successfully',
                'booking' => $booking,
            ], 200);

        } catch (ModelNotFoundException $e) {
            // If the booking is not found, return a 404 error
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }
    }

    /**
     * Delete a booking by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'message' => 'Booking deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            // If the booking is not found, return a 404 error
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }
    }
}
