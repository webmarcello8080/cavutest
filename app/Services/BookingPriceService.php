<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Price;
use Carbon\Carbon;

class BookingPriceService
{
    // I don't like this hardcoded number, this should be stored in the ENV file or in the DB as a options
    private int $totalSpaces = 10;

    /**
     * Calculate the price for a booking based on the given from and to dates.
     *
     * @param string $from
     * @param string $to
     * @return float
     */
    public function calculatePrice(string $from, string $to): float
    {
        $fromDate = \Carbon\Carbon::parse($from);
        $toDate = \Carbon\Carbon::parse($to);

        $totalPrice = 0.0;

        // Loop the dates to calculate the total price
        while ($fromDate <= $toDate) {
            $price = $this->getPriceForDate($fromDate);
            $totalPrice += $price;
            $fromDate->addDay();
        }

        return $totalPrice;
    }

    /**
     * Get the price for a specific date based on the season table.
     *
     * @param \Carbon\Carbon $date
     * @return float
     */
    private function getPriceForDate(\Carbon\Carbon $date): float
    {
        $price = Price::where('from', '<=', $date->toDateString())
                      ->where('to', '>=', $date->toDateString())
                      ->first();

        return $price ? $price->price : 0.0;
    }

    /**
     * Check available parking spaces for each day in the given date range.
     *
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getAvailableSpaces(string $from, string $to): array
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);
        $availability = [];

        while ($fromDate <= $toDate) {
            $bookedSpaces = Booking::whereDate('from', '<=', $fromDate)
                ->whereDate('to', '>=', $fromDate)
                ->count();

            $availability[$fromDate->toDateString()] = max(0, $this->totalSpaces - $bookedSpaces);
            $fromDate->addDay();
        }

        return $availability;
    }

    /**
     * Check if all days in the given range have available parking spaces.
     *
     * @param string $from
     * @param string $to
     * @return bool Returns true if all days have availability, false otherwise.
     */
    public function isDateRangeAvailable(string $from, string $to): ?string
    {
        $availability = $this->getAvailableSpaces($from, $to);
        foreach ($availability as $date => $spaces) {
            if ($spaces <= 0) {
                return false;
            }
        }
        return true;
    }
}
